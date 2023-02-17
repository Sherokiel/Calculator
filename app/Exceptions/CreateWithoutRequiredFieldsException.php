<?php

namespace App\Exceptions;

use Exception;
use Traits\locale;

class CreateWithoutRequiredFieldsException extends Exception
{
    use locale;

    public function __construct()
    {
        $message = $this->getText('exceptions', 'CreateWithoutRequiredFieldsException');

        parent::__construct($message);
    }
}