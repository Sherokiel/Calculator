<?php

namespace Tests\Support;

class AssertionExceptionExpectException extends AssertionException
{
    public function __construct($exceptionClass)
    {
        $actualValue = NULL;

        $message = "Failed assert that exception {$exceptionClass} had been throwed " . PHP_EOL;

        parent::__construct($exceptionClass, $actualValue, $message);
    }
}