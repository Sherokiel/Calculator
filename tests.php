<?php

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';

require prepare_file_path('tests/TestRun.php');
require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/UserRepository.php');
require prepare_file_path('app/Repositories/HistoryRepository.php');
require prepare_file_path('app/Services/CalculationService.php');
require prepare_file_path('app/Exceptions/CalculateUndefinedCommandsException.php');
require prepare_file_path('app/Exceptions/InvalidFieldException.php');
require prepare_file_path('app/Exceptions/CreateWithoutRequiredFieldsException.php');
require prepare_file_path('tests/supports/AssertionException.php');
require prepare_file_path('tests/supports/AssertionExceptionExpectException.php');
require prepare_file_path('tests/UserRepositoryTest.php');
require prepare_file_path('tests/HistoryRepositoryTest.php');
require prepare_file_path('tests/ServiceTest.php');

use Tests\UserRepositoryTest;
use Tests\HistoryRepositoryTest;
use Tests\ServiceTest;

putenv('JSON_STORAGE_PATH=test_data_storage');

try {
    (new UserRepositoryTest())->run();
} catch (Exception $error) {
    echo $error;
}

try {
    (new HistoryRepositoryTest())->run();
} catch (Exception $error) {
    echo $error;
}

try {
    (new ServiceTest())->run();
} catch (Exception $error) {
    echo $error;
}

readline();
