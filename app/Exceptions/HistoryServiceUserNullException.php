<?php

namespace App\Exceptions;

use Exception;
class HistoryServiceUserNullException extends Exception
{
    public function __construct()
    {
        parent::__construct("Field user cant be empty or null.");
    }
}