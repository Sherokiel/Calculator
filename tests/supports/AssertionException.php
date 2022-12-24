<?php

namespace Tests\Support;;

use Exception;

class AssertionException extends Exception
{
    public function __construct($expectedValue, $actualValue)
    {
        $message = 'Assertion error:' . PHP_EOL . 'Expected: ' . PHP_EOL . json_encode($expectedValue, JSON_PRETTY_PRINT) . PHP_EOL . ' Actual: ' . PHP_EOL . json_encode($actualValue, JSON_PRETTY_PRINT) . PHP_EOL;

        parent::__construct($message);
    }
}