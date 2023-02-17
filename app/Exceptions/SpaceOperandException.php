<?php

namespace App\Exceptions;

use App\Exceptions\OperandException;
use Traits\TranslationTrait;

class SpaceOperandException extends OperandException
{
    use TranslationTrait;
    public function __construct()
    {
        $message = $this->getText('errors', 'if_space');

        parent::__construct($message);
    }
}