<?php

namespace App\Exceptions;

use App\Exceptions\OperandExceptions;


class IfSpaceOperandException extends OperandExceptions
{
    public function __construct()
    {
        parent::__construct('Cant write space.' . PHP_EOL);
    }
}