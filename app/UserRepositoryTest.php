<?php

namespace Tests;

use App\Repositories\UserRepository;

class UserRepositoryTest
{
    public function __construct()
    {
        $this->dirName = getenv('JSON_STORAGE_PATH');
        $this->userRepository = new UserRepository();
    }

    public function run()
    {
        $this->testCreateCheckResult();
        $this->testCreateCheckDB();
        $this->testEntityFields();
        $this->testExtraFields();
    }

    protected function beforeTestsProcessing()
    {
        file_put_contents(prepare_file_path($this->dirName . '/users.json'), '');
    }

    protected function assertEquals($firstValue, $secondValue)
    {
        echo ($firstValue === $secondValue) ? 'Success' . PHP_EOL : 'Fail' . PHP_EOL;
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

    public function testEntityFields()
    {
        $dataTest = [
            'username' => 'username1',
        ];

        $data = $this->getJSONFixture('test_data_storage/users.json');

        $this->assertEquals($data, $dataTest);
    }

    public function testExtraFields()
    {
        $dataTest = [
            'username' => 'username1',
            'password' => 'password1',
            'date'=>'date'
        ];

        $data = $this->getJSONFixture('test_data_storage/users.json');

        $this->assertEquals($data, $dataTest);
    }

    public function getJSONFixture($path)
    {
        return json_decode(file_get_contents($path), true);
    }
}