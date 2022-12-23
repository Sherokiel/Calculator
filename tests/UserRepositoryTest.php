<?php

namespace Tests;

use App\Repositories\UserRepository;
use Exception;

class UserRepositoryTest
{
    public function __construct()
    {
        $this->dirName = getenv('JSON_STORAGE_PATH');
        $this->userRepository = new UserRepository();
    }

    public function run()
    {
        $methodsDone = 0;
        $methodsSuccessfully = 0;

        foreach (get_class_methods($this) as $method) {
            if (str_starts_with($method, 'test')) {
                $this->beforeTestsProcessing();
                $methodsDone++;

                echo "{$method}: ". PHP_EOL;

                try {
                    $this->$method();
                } catch (Exception $error) {
                    echo $error->getMessage();

                    continue;
                }
                $methodsSuccessfully++;

                echo PHP_EOL;
            }
        }

        $methodsFail = $methodsDone - $methodsSuccessfully;

        echo 'Total tests run: ' . $methodsDone . PHP_EOL . 'Completed: ' . $methodsSuccessfully . PHP_EOL . 'Failed: ' . $methodsFail . PHP_EOL;
    }

    protected function beforeTestsProcessing()
    {
        file_put_contents(prepare_file_path($this->dirName . '/users.json'), '');
    }

    protected function assertEquals($firstValue, $secondValue)
    {
        $result = $firstValue === $secondValue;

        if (!$result) {
            throw new Exception('Assertion error:' . PHP_EOL . 'Expected: ' . PHP_EOL . json_encode($secondValue, JSON_PRETTY_PRINT) . PHP_EOL . ' Actual: ' . PHP_EOL . json_encode($firstValue, JSON_PRETTY_PRINT) . PHP_EOL);
        }

        echo 'Success.' . PHP_EOL;

        return $result;
    }

    public function testCreateCheckResult()
    {
        $dataTest = $this->getJSONFixture('valid_create_data.json');
        $result = $this->userRepository->create($dataTest);

        $this->assertEquals($result, $dataTest);
    }

    public function testCreateCheckDB()
    {
        $dataTest = $this->getJSONFixture('valid_create_data.json');

        $this->userRepository->create($dataTest);
        $result = $this->getDataSet('users.json');

        $this->assertEquals([$result], [$dataTest]);
    }

    public function testCreateNotAllFields()
    {
        $dataTest = $this->getJSONFixture('not_all_fields_create_data.json');

        try {
            $this->userRepository->create($dataTest);
        } catch (Exception $error) {
            return $this->assertEquals($error->getMessage(), 'One of required fields does not filled.');
        }

        echo 'fail';

        return false;
    }

    public function testCreateExtraFields()
    {
        $dataTest = $this->getJSONFixture('extra_fields_create_data.json');
        $result = $this->userRepository->create($dataTest);

        $this->assertEquals($result, $this->getJSONFixture('valid_create_data.json'));
    }

    public function testCreateExtraFieldsBD()
    {
        $dataTest = $this->getJSONFixture('extra_fields_create_data.json');
        $this->userRepository->create($dataTest);
        $result = $this->getDataSet('users.json');

        $this->assertEquals($result, [$this->getJSONFixture('valid_create_data.json')]);
    }

    protected function getDataSet($data)
    {
        return json_decode(file_get_contents("test_data_storage/{$data}"), true);
    }

    protected function getJSONFixture($data)
    {
        return json_decode(file_get_contents("tests/fixtures/UserRepositoryTest/{$data}"), true);
    }

    protected function putJSONFixture($data, $fixtureName)
    {
        return file_put_contents($fixtureName, json_encode($data, JSON_PRETTY_PRINT));
    }
}