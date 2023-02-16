<?php

namespace Tests\Support;;

use Exception;

class AssertionException extends Exception
{
    public function __construct($expectedValue, $actualValue, $message = null)
    {
        if (is_array($expectedValue)) {
            $expectedValue = json_encode($expectedValue, JSON_PRETTY_PRINT);
        }

        if (is_array($actualValue)) {
            $actualValue = json_encode($actualValue, JSON_PRETTY_PRINT);
        }
        $message = $message ?? 'Assertion error:' . PHP_EOL . 'Expected:' . PHP_EOL . $expectedValue  . PHP_EOL . 'Actual:'  . PHP_EOL . $actualValue  . PHP_EOL;

        parent::__construct($message);
    }
}