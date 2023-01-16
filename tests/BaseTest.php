<?php

namespace Tests;

use Tests\Support\AssertionException;
use Tests\Support\AssertionExceptionExpectException;
use Exception;

class BaseTest
{
    public function __construct($fileName)
    {
        $this->dirName = getenv('JSON_STORAGE_PATH');
        $this->fileName = $fileName;
        $this->repositoryName = substr(strrchr(get_class($this), '\\'), 1);
    }

    public function run()
    {
        $testsCount = 0;
        $completedCount = 0;
        $keyword = 'test';
        $length = strlen($keyword);

        foreach (get_class_methods($this) as $method) {
            if (str_starts_with($method, $keyword)) {
                $this->beforeTestsProcessing();
                $testsCount++;

                echo $this->repositoryName . substr($method, $length) . ': ' . PHP_EOL;

                try {
                    $this->{$method}();
                } catch (AssertionException $error) {
                    echo $error->getMessage();

                    continue;
                }

                $completedCount++;

                echo 'Success.' . PHP_EOL . PHP_EOL;
            }
        }

        $methodsFail = $testsCount - $completedCount;

        echo 'Total tests run: ' . $testsCount . PHP_EOL . 'Completed: ' . $completedCount . PHP_EOL . 'Failed: ' . $methodsFail . PHP_EOL . PHP_EOL;

        if ($methodsFail > 0 && getenv('APP_ENV') === 'tests_runner') {
            exit(1);
        }
    }

    protected function beforeTestsProcessing()
    {
        foreach ($this->fileName as $dump) {
            if (file_exists("tests/fixtures/{$this->repositoryName}/dumps/{$dump}.json")) {
                $data = $this->getDataSet("tests/fixtures/{$this->repositoryName}/dumps/{$dump}.json");
                $this->putJSONFixture($this->dirName . "/{$dump}.json", $data);
            }
        }
    }

    protected function getDataSet($data)
    {
        return json_decode(file_get_contents($data), true);
    }

    protected function getJSONFixture($data)
    {
        return json_decode(file_get_contents("tests/fixtures/{$this->repositoryName}/{$data}"), true);
    }

    protected function putJSONFixture($fixtureName, $data)
    {
        return file_put_contents($fixtureName, json_encode($data, JSON_PRETTY_PRINT));
    }

    protected function assertEquals($expectedValue, $actualValue)
    {
        $result = $expectedValue === $actualValue;

        if (!$result) {
            throw new AssertionException($expectedValue, $actualValue);
        }

        return $result;
    }

    protected function assertExceptionThrowed($expectedExceptionClass, $expectedMessage, $callback)
    {
        try {
            $callback();
        } catch (Exception $error) {
            if ($error instanceof $expectedExceptionClass){
                $this->assertEquals($error->getMessage(), $expectedMessage);
            } else {
                throw new AssertionExceptionExpectException($expectedExceptionClass);
            }
        }
    }
}


