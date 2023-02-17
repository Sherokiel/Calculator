<?php

namespace App\Exceptions;

use App\Exceptions\OperandException;
use Traits\TranslationTrait;

class LetterOperandException extends OperandException
{
    use TranslationTrait;

    public function __construct()
    {
        $message = $this->getText('errors', 'if_letter');

        parent::__construct($message);
    }
}