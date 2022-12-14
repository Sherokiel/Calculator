<?php

namespace App\Exceptions;

use Exception;

class InvalidFieldException extends Exception
{
    public function __construct($field)
    {
        parent::__construct("Field {$field} is not valid.");
    }
}