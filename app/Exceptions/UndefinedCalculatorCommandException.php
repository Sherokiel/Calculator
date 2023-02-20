<?php

namespace App\Exceptions;

use Exception;
use Traits\TranslationTrait;

class UndefinedCalculatorCommandException extends Exception
{
    use TranslationTrait;

    public function __construct()
    {
        $message = $this->getText('errors', 'undefined_command');

        parent::__construct($message);
    }
}