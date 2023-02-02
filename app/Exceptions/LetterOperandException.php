<?php

namespace App\Exceptions;

use App\Exceptions\OperandException;

class LetterOperandException extends OperandException
{
    public function __construct()
    {
        parent::__construct('Cant write letter.');
    }
}