<?php

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';


require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/UserBaseRepository.php');

use App\Repositories\UserRepository;

putenv('dirName=test_data_storage');

testCreateValue([
    'username' => 'username',
    'password' => 'password'
]);

testDataCreate([
    'username' => 'username',
    'password' => 'password'
]);

readline();

function testCreate ($dataTest)
{
    $exmpClass = new UserRepository();

    file_put_contents('test_data_storage/users.json', '');

    return $exmpClass->create($dataTest);

}

function testCreateValue($dataTest)
{
    $data = testCreate($dataTest);

    if ($data === $dataTest) {
        echo 'Success' . "\n";
    } else {
        echo 'Fail' . "\n";
    }
}

function TestDataCreate($dataTest)
{
    testCreate($dataTest);

    $data = json_decode(file_get_contents('test_data_storage/users.json'), true);

    if ($data === [$dataTest]) {
        echo 'Success' . "\n";
    } else {
        echo 'Fail' . "\n";
    }
}