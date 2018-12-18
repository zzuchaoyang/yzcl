<?php

namespace App\Exceptions;

use Exception;

class GeneralException extends Exception
{
    private $redirect = null;

    public function __construct($message = '', $code = 400, Exception $previous = null)
    {
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
