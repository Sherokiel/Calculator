<?php

namespace Tests\Support;;

use Exception;

class AssertionException extends Exception
{
    public function __construct($expectedValue, $actualValue, $message = null)
    {
        $expectedMessage = PHP_EOL . json_encode($expectedValue, JSON_PRETTY_PRINT) . PHP_EOL;
        $actualMessage = PHP_EOL . json_encode($actualValue, JSON_PRETTY_PRINT) . PHP_EOL;
        $message = $message ?? 'Assertion error:' . PHP_EOL . "Expected: {$expectedMessage} Actual:  . {$actualMessage}";

        parent::__construct($message);
    }
}