<?php

namespace App\Exceptions;

use App\Exceptions\OperandExceptions;

class LetterOperandException extends OperandExceptions
{
    public function __construct()
    {
        parent::__construct('Cant write letter.' . PHP_EOL);
    }
}