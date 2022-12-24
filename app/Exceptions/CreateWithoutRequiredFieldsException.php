<?php

namespace App\Exception;

use Exception;

class CreateWithoutRequiredFieldsException extends Exception
{
    public function __construct()
    {
        parent::__construct('One of required fields does not filled.');
    }
}