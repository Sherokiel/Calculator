<?php

namespace Tests;

use App\Exceptions\CreateWithoutRequiredFieldsException;
use App\Exceptions\InvalidFieldException;
use App\Repositories\HistoryRepository;
use Exception;
use Tests\Support\AssertionException;
use Tests\Support\AssertionExceptionExpectException;

class HistoryRepositoryTest
{
    public function __construct()
    {
        $this->dirName = getenv('JSON_STORAGE_PATH');
        $this->historyRepository = new HistoryRepository();
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


                echo 'History' . substr($method, $length) . ': ' . PHP_EOL;

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
        $data = $this->getDataSet('perfect_value.json');
        var_dump($data);
        readline();
        $this->putJSONFixture(prepare_file_path($this->dirName . '/history.json'), json_encode($data, JSON_PRETTY_PRINT));
        //file_put_contents(prepare_file_path($this->dirName . '/history.json'), json_encode($data, JSON_PRETTY_PRINT));
    }

    protected function getDataSet($data)
    {
        return json_decode(file_get_contents("test_data_storage/{$data}"), true);
    }

    protected function getJSONFixture($data)
    {
        return json_decode(file_get_contents("tests/fixtures/HistoryRepositoryTest/{$data}"), true);
    }

    protected function putJSONFixture($fixtureName, $data)
    {
        return file_put_contents($fixtureName, json_encode($data, JSON_PRETTY_PRINT));
    }

    protected function assertEquals($firstValue, $secondValue)
    {
        $result = $firstValue === $secondValue;

        if (!$result) {
            throw new AssertionException($firstValue, $secondValue);
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

    public function testCreateCheckResult()
    {
        $dataTest = $this->getJSONFixture('valid_create_data.json');
        $result = $this->historyRepository->create($dataTest);

        $this->assertEquals($result, $dataTest);
    }

    public function testCreateCheckDB()
    {
        $dataTest = $this->getJSONFixture('valid_create_data.json');

        $this->historyRepository->create($dataTest);
        $usersState = $this->getDataSet('history.json');

        $this->assertEquals($usersState, [$dataTest]);
    }

    public function testCreateExtraFields()
    {
        $dataTest = $this->getJSONFixture('extra_fields_create_data.json');
        $result = $this->historyRepository->create($dataTest);

        $this->assertEquals($result, $this->getJSONFixture('valid_create_data.json'));
    }

    public function testCreateExtraFieldsBD()
    {
        $dataTest = $this->getJSONFixture('extra_fields_create_data.json');
        $this->historyRepository->create($dataTest);
        $result = $this->getDataSet('history.json');

        $this->assertEquals($result, [$this->getJSONFixture('valid_create_data.json')]);
    }

    public function testCreateNotAllFields()
    {
        $this->assertExceptionThrowed(CreateWithoutRequiredFieldsException::class, 'One of required fields does not filled.', function () {
            $data = $this->getJSONFixture('not_all_fields_create_data.json');

            $this->historyRepository->create($data);
        });
    }

    public function testGroupByInvalidFieldCheckThrowException()
    {
        $this->assertExceptionThrowed(InvalidFieldException::class, 'Field invalidField is not valid.', function () {
            $this->historyRepository->allGroupedBy('invalidField');
        });
    }


    public function testGroupedBy()
    {
        $dataTest[] =  $this->getJSONFixture('valid_unGroupedBy_data.json');
        //file_put_contents($this->dirName . '/history.json', json_encode($dataTest, JSON_PRETTY_PRINT));

        $result = $this->historyRepository->allGroupedBy('date');

        $this->assertEquals($result, $this->getJSONFixture('valid_GroupedBy_data.json'));
    }
}