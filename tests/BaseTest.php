<?php

namespace Tests;

use App\Services\HistoryService;
use Tests\Support\AssertionException;
use Tests\Support\AssertionExceptionExpectException;
use Exception;

class BaseTest
{
    public function __construct()
    {
        $this->dirName = getenv('JSON_STORAGE_PATH');
        $this->testClassName = substr(strrchr(get_class($this), '\\'), 1);
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

                echo $this->testClassName . substr($method, $length) . ': ' . PHP_EOL;

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
        if (is_dir("tests/fixtures/{$this->testClassName}/dumps")) {
            $dumps = scandir("tests/fixtures/{$this->testClassName}/dumps/");

            $dumps = array_filter($dumps, function ($key) {
                return !in_array($key, ['.', '..']);
            });

            foreach ($dumps as $dump) {
                if (file_exists("tests/fixtures/{$this->testClassName}/dumps/{$dump}")) {
                    $data = $this->getDataSet("tests/fixtures/{$this->testClassName}/dumps/{$dump}");

                    $this->putJSONFixture("{$this->dirName}/{$dump}", $data);
                }
            }
        }
    }

    protected function getDataSet($data)
    {
        return json_decode(file_get_contents($data), true);
    }

    protected function getJSONFixture($data)
    {
        return $this->getDataSet("tests/fixtures/{$this->testClassName}/{$data}");
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