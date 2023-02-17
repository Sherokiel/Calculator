<?php

namespace App\Exceptions;

use Exception;
use Traits\TranslationTrait;

class InvalidFieldException extends Exception
{
    use TranslationTrait;

    public function __construct($field)
    {
        $message = $this->getText('exceptions', 'InvalidFieldException', ['field' => $field]);

        parent::__construct($message);
    }
}