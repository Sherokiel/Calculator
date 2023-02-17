<?php

namespace App\Exceptions;

use App\Exceptions\OperandException;
use Traits\locale;

class LetterOperandException extends OperandException
{
    use locale;

    public function __construct()
    {
        $message = $this->getText('errors', 'if_letter');

        parent::__construct($message);
    }
}