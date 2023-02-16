<?php

namespace App\Exceptions;

use Exception;
use Traits\locale;

class InvalidFieldException extends Exception
{
    use locale;

    public function __construct($field)
    {
        $message = $this->getText('exceptions', 'InvalidFieldException', ['field' => $field]);

        parent::__construct($message);
    }
}