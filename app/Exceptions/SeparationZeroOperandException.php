<?php

namespace App\Exceptions;

use App\Exceptions\OperandException;
use Traits\TranslationTrait;

class SeparationZeroOperandException extends OperandException
{
    use TranslationTrait;
    public function __construct()
    {
        $message = $this->getText('errors', 'if_separate_zero');

        parent::__construct($message);
    }
}