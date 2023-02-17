<?php

namespace App\Exceptions;

use Exception;
use Traits\TranslationTrait;

class CreateWithoutRequiredFieldsException extends Exception
{
    use TranslationTrait;

    public function __construct()
    {
        $message = $this->getText('exceptions', 'CreateWithoutRequiredFieldsException');

        parent::__construct($message);
    }
}