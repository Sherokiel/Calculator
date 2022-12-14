<?php

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';

require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/UserRepository.php');
require prepare_file_path('app/Exceptions/InvalidFieldException.php');
require prepare_file_path('app/Exceptions/CreateWithoutRequiredFieldsException.php');
require prepare_file_path('tests/supports/AssertionException.php');
require prepare_file_path('tests/supports/AssertionExceptionExpectException.php');
require prepare_file_path('tests/UserRepositoryTest.php');

use Tests\UserRepositoryTest;

putenv('JSON_STORAGE_PATH=test_data_storage');

try {
    (new UserRepositoryTest())->run();
} catch (Exception $error) {
    echo $error;
}

readline();
