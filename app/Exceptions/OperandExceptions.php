<?php

namespace App\Exceptions;

use Exception;

class OperandExceptions extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message . PHP_EOL);
    }
}