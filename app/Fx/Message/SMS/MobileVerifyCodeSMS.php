<?php

namespace App\Fx\Message\SMS;

use App\Fx\Message\SMSSender;
use Cache;

class MobileVerifyCodeSMS extends SMS
{
    /**
     * @var array 短信模板的配置信息, key 是使用的短信商名称, value 是对应的配置文件的路径
     */
    protected $templateConfig = [
        SMSSender::AGENT_SUBMAIL => 'phpsms.agents.SubMail.verifySmsTemplateId',
    ];

    private $code;

    private $key;

    private $minutes = 2;

    private $maxAttempts = 3;

    private $resendSeconds = 60;

    public function init(...$args)
    {
        $mobileOrUser    = $args[0];
        $this->mobile    = $mobileOrUser;
        $this->templates = $this->getTemplates();
        $this->key       = $this->generateKey();

        return $this;
    }

    /**
     * 发送前的的缓存内容和验证是否允许发送
     */
    public function sending()
    {
        // 如果缓存没有验证码则直接返回
        if (!Cache::has($this->key)) {
            $this->code = $this->generateCode();
            $this->cacheVerifyInfo();
        } else {
            $verifyInfo = Cache::get($this->key);
            // 判断是否允许发送,默认时间间隔内不许发送
            if ((time() - $verifyInfo['time']) >= $this->resendSeconds) {
                $this->code         = $this->generateCode();
                $verifyInfo['code'] = $this->code;
                $verifyInfo['time'] = time();
                Cache::put($this->key, $verifyInfo);
            } else {
                $this->isAllowedSent = false;
                $remainSeconds       = $this->resendSeconds - (time() - $verifyInfo['time']);
                $this->failMessage   = "请求太频繁,请在{$remainSeconds}秒之后再试!";
            }
        }

        // 当天发送验证码次数
        $verifyCodeSentTimes = Cache::get("verifyCode:sentTimes:{$this->mobile}");

        if ($verifyCodeSentTimes && $verifyCodeSentTimes >= 50) {
            $this->isAllowedSent = false;
            $this->failMessage   = '您今天已经发送验证码 5 次了，不能再发送了！';
        }


        if ($this->isAllowedSent) {
            !Cache::has("verifyCode:sentTimes:{$this->mobile}")
            && Cache::put("verifyCode:sentTimes:{$this->mobile}", 0, today()->endOfDay());
            Cache::increment("verifyCode:sentTimes:{$this->mobile}");
        }

        $this->data = [
            'code'    => $this->code,
            'minutes' => $this->minutes,
        ];
    }

    /**
     * 用来验证验证码是否正确.
     *
     * @param $code
     *
     * @return array
     */
    public function verifyCode($code)
    {
        if (Cache::has($this->key)) {
            $verifyInfo            = Cache::get($this->key);
            $verifyInfo['attempt'] += 1;
            // 更新验证码次数
            Cache::put($this->key, $verifyInfo);
            if ($verifyInfo['attempt'] > 3) {
                return [
                    'success' => false,
                    'message' => "该验证码尝试了{$this->maxAttempts}以上,请尝试重新发送验证码",
                ];
            }

            if ($code != $verifyInfo['code']) {
                return [
                    'success' => false,
                    'message' => '验证错误请重试!',
                ];
            } else {
                // 验证成功后需要忘记该验证码,避免重复使用
                Cache::forget($this->key);

                return [
                    'success' => true,
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => '请重新发送验证码!',
            ];
        }
    }

    /**
     * 生成验证码
     *
     * @return string
     */
    private function generateCode()
    {
        $length       = 4;
        $characters   = '0123456789';
        $charLength   = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[mt_rand(0, $charLength - 1)];
        }

        return $randomString;
    }

    /**
     * 生成缓存的KEY.
     *
     * @return string
     */
    private function generateKey()
    {
        return "verify_code:mobile:{$this->mobile}";
    }

    /**
     * 缓存验证信息.
     */
    private function cacheVerifyInfo()
    {
        $cacheMins = $this->minutes;

        Cache::put($this->key, [
            'code'    => $this->code,
            'attempt' => 0,
            'time'    => time(),
        ], $cacheMins);
    }


    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }
}
