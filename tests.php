<?php

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';

require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/UserBaseRepository.php');

use App\Repositories\UserRepository;

putenv('JSON_STORAGE_PATH=test_data_storage');

testCreateCheckResult([
    'username' => 'username',
    'password' => 'password'
]);

testCreateCheckDB([
    'username' => 'username',
    'password' => 'password'
]);

readline();

function beforeTestsProcessing()
{
    $exmpClass = new UserRepository();
    $dirName = getenv('JSON_STORAGE_PATH');

    file_put_contents(prepare_file_path($dirName . '/users.json'), '');

    return $exmpClass;
}

function assertEquals($firstValue, $secondValue)
{
    echo ($firstValue === $secondValue) ? 'Success' . PHP_EOL : 'Fail' . PHP_EOL;
}

function testCreateCheckResult($dataTest)
{
    $data = beforeTestsProcessing()->create($dataTest);

    assertEquals([$data], $dataTest);
}

function testCreateCheckDB($dataTest)
{
    beforeTestsProcessing()->create($dataTest);

    $data = json_decode(file_get_contents('test_data_storage/users.json'), true);

    assertEquals($data, $dataTest);
}