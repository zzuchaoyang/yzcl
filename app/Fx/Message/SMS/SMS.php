<?php

namespace App\Fx\Message\SMS;

abstract class SMS
{
    public $templates;

    protected $templateConfig = [];

    public $mobile;

    public $isAllowedSent = true;

    public $failMessage = '';

    public $data = [];

    /**
     * 业务类型.
     *
     * @var string
     */
    protected $businessType = null;

    //abstract public function __construct();

    abstract public function init(...$args);

    protected function getTemplates()
    {
        $templates = [];

        foreach ($this->templateConfig as $key => $item) {
            $templates[$key] = config($item);
        }

        return $templates;
    }

    /**
     * 触发器.
     */

    /**
     * 发送短信成功后的操作.
     *
     * @param $resule
     */
    public function success($resule)
    {
    }

    /**
     * 当然还有发送前置操作和失败时需要处理的事情
     */
    public function sending()
    {
    }

    /**
     * @param $resule
     */
    public function error($resule)
    {
    }
}
