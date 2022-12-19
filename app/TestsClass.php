<?php

namespace App;

use App\Repositories\UserRepository;
class TestsClass
{
    public function __construct()
    {
        $this->dirName = getenv('JSON_STORAGE_PATH');
    }

    protected function beforeTestsProcessing()
    {
        $exmpClass = new UserRepository();

        file_put_contents(prepare_file_path($this->dirName . '/users.json'), '');

        return $exmpClass;
    }

    protected function assertEquals($firstValue, $secondValue)
    {
        var_dump($firstValue);
        var_dump($secondValue);
        echo ($firstValue === $secondValue) ? 'Success' . PHP_EOL : 'Fail' . PHP_EOL;
    }

    public function testCreateCheckResult($dataTest)
    {
        $data = $this->beforeTestsProcessing()->create($dataTest);

        $this->assertEquals($data, $dataTest);
    }

    public function testCreateCheckDB($dataTest)
    {
        $this->beforeTestsProcessing()->create($dataTest);

        $data = json_decode(file_get_contents('test_data_storage/users.json'), true);

        $this->assertEquals($data, [$dataTest]);
    }
}
