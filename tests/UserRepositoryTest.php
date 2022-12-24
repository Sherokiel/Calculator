<?php

namespace Tests;

use Exception;
use App\Repositories\UserRepository;
use App\Exceptions\CreateWithoutRequiredFieldsException;
use App\Exceptions\InvalidFieldException;
use Tests\Support\AssertionException;

class UserRepositoryTest
{
    public function __construct()
    {
        $this->dirName = getenv('JSON_STORAGE_PATH');
        $this->userRepository = new UserRepository();
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


                echo substr($method, $length) . ': ' . PHP_EOL;

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

        echo 'Total tests run: ' . $testsCount . PHP_EOL . 'Completed: ' . $completedCount . PHP_EOL . 'Failed: ' . $methodsFail . PHP_EOL;

        if ($methodsFail > 0 && getenv('APP_ENV') === 'tests_runner') {
            throw new Exception('Tests failed.');
        }
    }

    protected function beforeTestsProcessing()
    {
        file_put_contents(prepare_file_path($this->dirName . '/users.json'), '');
    }

    protected function assertEquals($firstValue, $secondValue)
    {
        $result = $firstValue === $secondValue;

        if (!$result) {
            throw new AssertionException($firstValue, $secondValue);
        }

        return $result;
    }

    public function testGithubRunner()
    {
        $this->assertEquals(true, false);
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
        $usersState = $this->getDataSet('users.json');

        $this->assertEquals($usersState, [$dataTest]);
    }

    public function testCreateNotAllFields()
    {
        $dataTest = $this->getJSONFixture('not_all_fields_create_data.json');

        try {
            $this->userRepository->create($dataTest);
        } catch (CreateWithoutRequiredFieldsException $error) {
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

    public function testGroupByInvalidFieldCheckThrowException()
    {
        try {
            $this->userRepository->allGroupedBy('invalidField');
        } catch (InvalidFieldException $error) {
            return $this->assertEquals($error->getMessage(), 'Field invalidField is not valid.');
        }

        echo 'fail';

        return false;
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
