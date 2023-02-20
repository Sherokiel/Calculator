<?php

namespace App\Exceptions;

use Exception;

class CreateHistoryEmptyUserException extends Exception
{
    public function __construct()
    {
        parent::__construct('History can not been created without auth user.');
    }
}