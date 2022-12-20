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
        foreach (get_class_methods(new UserRepositoryTest()) as $method) {
            if(str_starts_with($method, 'test')) {
                $this->$method();
            }
        }
    }

    protected function beforeTestsProcessing()
    {
        file_put_contents(prepare_file_path($this->dirName . '/users.json'), '');
    }

    protected function assertEquals($firstValue, $secondValue)
    {
        $assertEquals = $firstValue === $secondValue;

        echo ($assertEquals) ? 'Success' . PHP_EOL : 'Fail' . PHP_EOL;

        return ($assertEquals);
    }

    public function testCreateCheckResult()
    {
        $this->beforeTestsProcessing();

        $dataTest = [
            'username' => 'username1',
            'password' => 'password1'
        ];

        $data = $this->userRepository->create($dataTest);

        $this->assertEquals($data, $dataTest);
    }

    public function testCreateCheckDB()
    {
        $this->beforeTestsProcessing();

        $dataTest = [
            'username' => 'username1',
            'password' => 'password1'
        ];

        $this->userRepository->create($dataTest);

        $data = $this->getJSONFixture('test_data_storage/users.json');

        $this->assertEquals($data, [$dataTest]);
    }

    public function testCreateNotAllFields()
    {
        $dataTest = [
            'username' => 'username1',
        ];

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

        $dataTest = [
            'username' => 'username1',
            'password' => 'password1',
            'date' => 'date'
        ];

        $data = $this->userRepository->create($dataTest);

        $this->assertEquals($data, ['username' => 'username1', 'password' => 'password1']);
    }

    public function testCreateExtraFieldsBD()
    {
        $this->beforeTestsProcessing();

        $dataTest = [
            'username' => 'username1',
            'password' => 'password1',
            'date' => 'date'
        ];

        $this->userRepository->create($dataTest);
        $data = $this->getJSONFixture('test_data_storage/users.json');

        $this->assertEquals($data, [['username' => 'username1', 'password' => 'password1']]);
    }

    public function getJSONFixture($path)
    {
        return json_decode(file_get_contents($path), true);
    }
}