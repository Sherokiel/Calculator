<?php

namespace App\Supports;

use Exception;

class CreateException extends Exception
{
    public function __construct()
    {
        $message = 'One of required fields does not filled.';

        parent::__construct($message);
    }
}