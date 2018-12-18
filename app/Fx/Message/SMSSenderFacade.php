<?php

namespace App\Fx\Message;

use Illuminate\Support\Facades\Facade;

class SMSSenderFacade extends Facade
{
    /**
     * Get the registered name of the sms sender.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sms-sender';
    }
}
