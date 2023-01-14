<?php

namespace Tests;

use Tests\Support\AssertionException;
use Tests\Support\AssertionExceptionExpectException;
use Exception;

class Tests
{
    public function __construct($repositoryName, $fileName)
    {
        $this->dirName = getenv('JSON_STORAGE_PATH');
        $this->fileName = $fileName;
        $this->repositoryName = $repositoryName;
    }
    public function run()
    {
        $testsCount = 0;
        $completedCount = 0;
        $keyword = 'test';
        $length = strlen($keyword);

        foreach (get_class_methods($this) as $method) {
            if (str_starts_with($method, $keyword)) {
                if (file_exists("{$this->dirName}/{$this->fileName}_perfect_value.json")) {

                    $this->beforeTestsProcessing();
                }

                $testsCount++;


                echo $this->fileName . substr($method, $length) . ': ' . PHP_EOL;

                try {
                    $this->$method();
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
        $data = $this->getDataSet("{$this->fileName}_perfect_value.json");

        $this->putJSONFixture($this->dirName . "/{$this->fileName}.json", $data);
    }

    protected function getDataSet($data)
    {
        return json_decode(file_get_contents("test_data_storage/{$data}"), true);
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


