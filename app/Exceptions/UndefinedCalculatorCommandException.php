<?php

namespace App\Exceptions;

use Exception;

class UndefinedCalculatorCommandException extends Exception
{
    public function __construct()
    {
        parent::__construct('Undefined command.');
    }
}