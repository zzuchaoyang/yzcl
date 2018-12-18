<?php

namespace App\Fx\Message;

use App\Exceptions\GeneralException;
use Log;
use ReflectionClass;
use Toplan\PhpSms\Sms as SmsAgent;

class SMSSender
{
    const AGENT_SUBMAIL = 'SubMail';

    /**
     * @var SmsAgent 发送短信的提供者
     */
    protected $smsAgent;

    public function __construct(SmsAgent $smsAgent)
    {
        $this->smsAgent = $smsAgent;
    }

    /**
     * 发送短信
     *
     * @param mixed $sms 短信业务类
     * @return mixed
     * @throws GeneralException
     * @throws \ReflectionException
     */
    public function send($sms)
    {
        // 发短信前置操作
        if ((new ReflectionClass($sms))->hasMethod('sending')) {
            $sms->sending();
        }

        // 验证短信是否允许被发送
        if (!$sms->isAllowedSent) {
            throw new GeneralException($sms->failMessage);
        }

        // 只有生产环境才发短信
        if (app()->environment('production')) {
            // 如果是有原生短信数据则直接发送
            if (isset($sms->RAWData) && $sms->RAWData) {
                foreach ($sms->RAWData as $data) {
                    $result = $this->smsAgent
                        ->to($data['mobile'])
                        ->template($sms->templates)
                        ->data($data)
                        ->send();
                }
            } else {
                // 可能发送给多人
                if (is_array($sms->mobile)) {
                    foreach ($sms->mobile as $mobile) {
                        // 发送短信
                        $result = $this->smsAgent
                            ->to($mobile)
                            ->template($sms->templates)
                            ->data($sms->data)
                            ->send();
                    }
                } else {
                    $result = $this->smsAgent
                        ->to($sms->mobile)
                        ->template($sms->templates)
                        ->data($sms->data)
                        ->send();
                }
            }
        } else {
            Log::debug('发送短信', [
                'data' => isset($sms->RAWData) ? $sms->RAWData : [
                    $sms->mobile,
                    $sms->data,
                ],
            ]);
            $result = [
                'success' => true,
            ];
        }

        // 发送成功后触发成功操作
        if (isset($result['success']) && $result['success']) {
            if ((new ReflectionClass($sms))->hasMethod('success')) {
                $sms->success($result);
            }

            // 返回操作结果
            return array_only($result, 'success');
        } // 失败后触发失败操作
        else {
            if ((new ReflectionClass($sms))->hasMethod('error')) {
                $sms->error($result);
            }

            $errorMessage = isset($result['result']['info']) ?: '未知错误';

            Log::info('短信通知出错:', ['msg' => $errorMessage]);
        }
    }
}
