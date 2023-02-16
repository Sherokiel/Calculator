<?php

namespace App\Exceptions;

use App\Exceptions\OperandException;
use Traits\locale;

class LetterOperandException extends OperandException
{
    use locale;

    public function __construct()
    {
        $message = $this->getLocaleText();

        parent::__construct($message['errors']['if_letter']);
    }
}