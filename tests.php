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

function testCreate ($dataTest)
{
    $exmpClass = beforeTestsProcessing();

    return $exmpClass->create($dataTest);
}

function beforeTestsProcessing()
{
    $exmpClass = new UserRepository();
    $dirName = getenv('JSON_STORAGE_PATH');

    file_put_contents(prepare_file_path($dirName . '/users.json'), '');

    return $exmpClass;
}

function assertEquals($firstValue, $secondValue)
{
    if ($firstValue === [$secondValue]) {
        echo 'Success' . "\n";
    } else {
        echo 'Fail' . "\n";
    }
}

function testCreateCheckResult($dataTest)
{
    $data[] = testCreate($dataTest);

    assertEquals($data, $dataTest);
}

function testCreateCheckDB($dataTest)
{
    testCreate($dataTest);

    $data = json_decode(file_get_contents('test_data_storage/users.json'), true);

    assertEquals($data, $dataTest);
}