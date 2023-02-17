<?php

namespace App\Exceptions;

use App\Exceptions\OperandException;
use Traits\locale;

class SeparationZeroOperandException extends OperandException
{
    use locale;
    public function __construct()
    {
        $message = $this->getText('errors', 'if_separate_zero');

        parent::__construct($message);
    }
}