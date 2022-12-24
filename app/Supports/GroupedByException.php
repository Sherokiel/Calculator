<?php

namespace App\Supports;

use Exception;

class GroupedByException extends Exception
{
    public function __construct($field)
    {
        $message = "Field {$field} is not valid.";

        parent::__construct($message);
    }
}