<?php

namespace App\Exceptions;

use Exception;
use Traits\locale;

class CreateWithoutRequiredFieldsException extends Exception
{
    use locale;
    protected $message;
    protected $settings;
    public function __construct()
    {
        //$message = $this->getText1('exceptions', 'CreateWithoutRequiredFieldsException');
        $message = $this->getText();
        //$message = ['exceptions'=>['CreateWithoutRequiredFieldsException' => 'pizdec']];
        //parent::__construct('One of required fields does not filled.');
        parent::__construct($message['exceptions']['CreateWithoutRequiredFieldsException']);
    }


}