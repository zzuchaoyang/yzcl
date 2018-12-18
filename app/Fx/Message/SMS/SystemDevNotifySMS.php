<?php

namespace App\Fx\Message\SMS;

use App\Fx\Message\SMSSender;

class SystemDevNotifySMS extends SMS
{
    const ZZC = '18100326316';  // 朱智超
    const WLT = '15517515025';  // 王林涛

    protected $businessType = null;

    /**
     * @var array 短信模板的配置信息, key 是使用的短信商名称, value 是对应的配置文件的路径
     */
    protected $templateConfig = [
        SMSSender::AGENT_SUBMAIL => 'phpsms.agents.SubMail.template.system_dev_notify',
    ];

    /**
     * 构造函数,将需要的数据信息传入.
     *
     * RestPasswordSMS constructor.
     *
     * @param $mobile
     * @param $message
     */
    public function __construct($mobile, $message)
    {
        $this->mobile    = $mobile;
        $this->templates = $this->getTemplates();
        $this->data      = [
            'message' => $message,
        ];
    }

    /**
     * @param null $businessType
     */
    public function setBusinessType($businessType)
    {
        $this->businessType = $businessType;
    }

    public function init(...$args)
    {
        // TODO: Implement init() method.
    }
}
