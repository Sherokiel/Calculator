<?php

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';

require prepare_file_path('tests/BaseTest.php');
require prepare_file_path('app/interfaces/ExporterInterface.php');
require prepare_file_path('app/interfaces/BaseRepositoryInterface.php');
require prepare_file_path('app/Exporters/BaseHistoryExporter.php');
require prepare_file_path('app/Exporters/HistoryConsoleExporter.php');
require prepare_file_path('app/Exporters/HistoryTxtExporter.php');
require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/UserRepository.php');
require prepare_file_path('app/Repositories/IniBaseRepository.php');
require prepare_file_path('app/Repositories/SettingsRepository.php');
require prepare_file_path('app/Repositories/HistoryRepository.php');
require prepare_file_path('app/Services/CalculatorService.php');
require prepare_file_path('app/Services/HistoryService.php');
require prepare_file_path('app/Traits/TranslationTrait.php');
require prepare_file_path('app/Exceptions/CreateHistoryEmptyUserException.php');
require prepare_file_path('app/Exceptions/UndefinedCalculatorCommandException.php');
require prepare_file_path('app/Exceptions/InvalidFieldException.php');
require prepare_file_path('app/Exceptions/CreateWithoutRequiredFieldsException.php');
require prepare_file_path('tests/supports/AssertionException.php');
require prepare_file_path('tests/supports/AssertionExceptionExpectException.php');
require prepare_file_path('tests/UserRepositoryTest.php');
require prepare_file_path('tests/HistoryRepositoryTest.php');
require prepare_file_path('tests/CalculatorServiceTest.php');
require prepare_file_path('tests/HistoryServiceTest.php');

use Tests\UserRepositoryTest;
use Tests\HistoryRepositoryTest;
use Tests\CalculatorServiceTest;
use Tests\HistoryServiceTest;

putenv('INI_STORAGE_PATH=test_settings');
putenv('JSON_STORAGE_PATH=test_data_storage');
putenv('APP_ENV=testing');

try {
    (new HistoryRepositoryTest())->run();
    (new UserRepositoryTest())->run();
    (new CalculatorServiceTest())->run();
    (new HistoryServiceTest())->run();
} catch (Exception $error) {
    echo $error;
}

readline();
