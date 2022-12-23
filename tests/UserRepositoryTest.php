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

        foreach (get_class_methods($this) as $method) {
            if (str_starts_with($method, 'test')) {

                $this->beforeTestsProcessing();

                echo "{$method}: ". PHP_EOL;
                $this->$method();
                $methodsDone++;
            }
        }

        echo "Completed tests count: {$methodsDone}";
    }

    protected function beforeTestsProcessing()
    {
        file_put_contents(prepare_file_path($this->dirName . '/users.json'), '');
    }

    protected function assertEquals($firstValue, $secondValue)
    {
        $result = $firstValue === $secondValue;

        echo ($result) ? 'Success.' . PHP_EOL : 'Fail.' . PHP_EOL;

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

        $this->assertEquals($result, [$dataTest]);
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

        $this->assertEquals($result, ['username' => 'username1', 'password' => 'password1']);
    }

    public function testCreateExtraFieldsBD()
    {
        $dataTest = $this->getJSONFixture('extra_fields_create_data.json');
        $this->userRepository->create($dataTest);
        $result = $this->getDataSet('users.json');

        $this->assertEquals($result, [['username' => 'username1', 'password' => 'password1']]);
    }

    public function getDataSet($data)
    {
        return json_decode(file_get_contents("test_data_storage/{$data}"), true);
    }

    public function getJSONFixture($data)
    {
        return json_decode(file_get_contents("tests/fixtures/UserRepositoryTest/{$data}"), true);
    }

    protected function putJSONFixture($data, $fixtureName)
    {
        return file_put_contents($fixtureName, json_encode($data, JSON_PRETTY_PRINT));
    }
}