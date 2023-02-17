<?php

namespace App\Exceptions;

use App\Exceptions\OperandException;
use Traits\locale;

class SpaceOperandException extends OperandException
{
    use locale;
    public function __construct()
    {
        $message = $this->getText('errors', 'if_space');

        parent::__construct($message);
    }
}