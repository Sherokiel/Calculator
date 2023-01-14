<?php

namespace App\Exceptions;

use App\Exceptions\OperandExceptions;

class SeparateZeroOperandException extends OperandExceptions
{
    public function __construct()
    {
        parent::__construct('Cant separate on zero.' . PHP_EOL);
    }
}