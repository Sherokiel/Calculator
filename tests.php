<?php

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';


require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/UserBaseRepository.php');

use App\Repositories\UserRepository;

putenv('dirName = test_data_storage');

testCreate('userName', 'password');

readline();

function testCreate($userName, $password)
{
    $exmpClass = new UserRepository();

    file_put_contents('test_data_storage/users.json', '');

    $dataTest = $exmpClass->create([
        'username' => $userName,
        'password' => $password
    ]);

    $data = json_decode(file_get_contents('test_data_storage/users.json'), true);

    if( $dataTest === $data[0]) {
        echo 'Success';
    } else {
        echo 'Fail';
    }
}