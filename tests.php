<?php

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';

require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/UserBaseRepository.php');
require prepare_file_path('tests/UserRepositoryTest.php');
require prepare_file_path('tests/supports/AssertionException.php');

use Tests\UserRepositoryTest;

putenv('JSON_STORAGE_PATH=test_data_storage');

(new UserRepositoryTest())->run();

readline();
