<?php

namespace App\Exceptions;

use App\Exceptions\OperandException;

class SeparationZeroOperandException extends OperandException
{
    public function __construct()
    {
        parent::__construct('Cant separate on zero.');
    }
}