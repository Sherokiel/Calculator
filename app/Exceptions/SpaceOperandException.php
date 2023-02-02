<?php

namespace App\Exceptions;

use App\Exceptions\OperandException;

class SpaceOperandException extends OperandException
{
    public function __construct()
    {
        parent::__construct('Cant write space.');
    }
}