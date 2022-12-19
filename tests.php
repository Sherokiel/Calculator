<?php

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';

require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/UserBaseRepository.php');
require prepare_file_path('app/TestsClass.php');

use App\TestsClass;

putenv('JSON_STORAGE_PATH=test_data_storage');

$tests = new TestsClass();

$tests->testCreateCheckResult();

$tests->testCreateCheckDB();

readline();