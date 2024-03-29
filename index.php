<?php

use App\Application;

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';
require prepare_file_path('app/Application.php');
require prepare_file_path('app/Services/CalculatorService.php');
require prepare_file_path('app/Services/HistoryService.php');
require prepare_file_path('app/interfaces/ExporterInterface.php');
require prepare_file_path('app/interfaces/BaseRepositoryInterface.php');
require prepare_file_path('app/Exporters/BaseHistoryExporter.php');
require prepare_file_path('app/Exporters/HistoryConsoleExporter.php');
require prepare_file_path('app/Exporters/HistoryTxtExporter.php');
require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/IniBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/UserRepository.php');
require prepare_file_path('app/Repositories/HistoryRepository.php');
require prepare_file_path('app/Repositories/SettingsRepository.php');
require prepare_file_path('app/Traits/TranslationTrait.php');
require prepare_file_path('app/Exceptions/InvalidFieldException.php');
require prepare_file_path('app/Exceptions/CreateHistoryEmptyUserException.php');
require prepare_file_path('app/Exceptions/CreateWithoutRequiredFieldsException.php');
require prepare_file_path('app/Exceptions/UndefinedCalculatorCommandException.php');
require prepare_file_path('app/Exceptions/OperandException.php');
require prepare_file_path('app/Exceptions/LetterOperandException.php');
require prepare_file_path('app/Exceptions/SpaceOperandException.php');
require prepare_file_path('app/Exceptions/SeparationZeroOperandException.php');
require prepare_file_path('libraries/console_helpers.php');
require prepare_file_path('constants.php');

putenv('INI_STORAGE_PATH=settings');
putenv('JSON_STORAGE_PATH=data_storage');
putenv('APP_ENV=locale');

try {
    (new Application())->run();
} catch (Exception $error) {
    echo $error;

    readline();
}
