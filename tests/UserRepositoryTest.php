<?php

namespace Tests;

use App\Repositories\UserRepository;
use Exception;

class UserRepositoryTest
{
    protected $fixturePath = 'tests/fixtures/UserRepositoryTest/';
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
        $this->beforeTestsProcessing();

        $dataTest = $this->getJSONFixture("{$this->fixturePath}valid_create_data.json");
        $data = $this->userRepository->create($dataTest);

        $this->assertEquals($data, $dataTest);
    }

    public function testCreateCheckDB()
    {
        $this->beforeTestsProcessing();

        $dataTest = $this->getJSONFixture("{$this->fixturePath}valid_create_data.json");
        $this->userRepository->create($dataTest);
        $data = $this->getJSONFixture('test_data_storage/users.json');

        $this->assertEquals($data, [$dataTest]);
    }

    public function testCreateNotAllFields()
    {
        $dataTest = $this->getJSONFixture("{$this->fixturePath}not_all_fields_create_data.json");

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
        $this->beforeTestsProcessing();

        $dataTest = $this->getJSONFixture("{$this->fixturePath}extra_fields_create_data.json");
        $data = $this->userRepository->create($dataTest);

        $this->assertEquals($data, ['username' => 'username1', 'password' => 'password1']);
    }

    public function testCreateExtraFieldsBD()
    {
        $this->beforeTestsProcessing();

        $dataTest = $this->getJSONFixture("{$this->fixturePath}extra_fields_create_data.json");
        $this->userRepository->create($dataTest);
        $data = $this->getJSONFixture('test_data_storage/users.json');

        $this->assertEquals($data, [['username' => 'username1', 'password' => 'password1']]);
    }

    public function getJSONFixture($path)
    {
        return json_decode(file_get_contents($path), true);
    }

    public function putJSONFixture($data, $path)
    {
        return file_put_contents($path,json_encode($data, JSON_PRETTY_PRINT));
    }
}