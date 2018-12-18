<?php

namespace App\Exceptions;

use Exception;

class GeneralContinueException extends Exception
{
    private $redirect = null;

    public function __construct($message = '', $code = 200, Exception $previous = null)
    {
        $message = $message."(30{$code})";
        parent::__construct($message, $code, $previous);
    }

    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param null $redirect
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }
}
