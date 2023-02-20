<?php

namespace App\Exceptions;

use Exception;
use Traits\TranslationTrait;

class CreateWithoutRequiredFieldsException extends Exception
{
    use TranslationTrait;

    public function __construct()
    {
        $message = $this->getText('exceptions', 'create_without_required_fields');

        parent::__construct($message);
    }
}