<?php
namespace App\Exceptions;

use Exception;

class CaptchaException extends Exception
{
    public function __construct($message = 'error', $code = 500)
    {
        $message = "Captcha ". $message;
        parent::__construct($message, $code);
    }
}
