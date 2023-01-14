<?php

namespace App\Exceptions;

use Exception;
class CalculateUndefinedCommandsException extends Exception
{
    public function __construct()
    {
        parent::__construct('Undefined command.');
    }
}