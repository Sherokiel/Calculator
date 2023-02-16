<?php

namespace App\Exceptions;

use Exception;
use Traits\locale;

class UndefinedCalculatorCommandException extends Exception
{
    use locale;

    public function __construct()
    {
        $message = $this->getLocaleText();

        parent::__construct($message['errors']['undefined_command']);
    }
}