<?php

namespace App\Exceptions;

use Exception;
use Traits\locale;

class UndefinedCalculatorCommandException extends Exception
{
    use locale;

    public function __construct()
    {
        $message = $this->getText('errors', 'undefined_command');

        parent::__construct($message);
    }
}