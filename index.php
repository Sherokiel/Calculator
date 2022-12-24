<?php

use App\Application;

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';
require prepare_file_path('app/Application.php');
require prepare_file_path('app/Exporters/BaseHistoryExporter.php');
require prepare_file_path('app/Exporters/HistoryConsoleExporter.php');
require prepare_file_path('app/Exporters/HistoryTxtExporter.php');
require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/IniBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/UserRepository.php');
require prepare_file_path('app/Repositories/HistoryRepository.php');
require prepare_file_path('app/Repositories/SettingsRepository.php');
require prepare_file_path('app/Supports/CreateException.php');
require prepare_file_path('app/Supports/GroupedByException.php');
require prepare_file_path('libraries/console_helpers.php');
require prepare_file_path('constants.php');

putenv('JSON_STORAGE_PATH=data_storage');

try {
    (new Application())->run();
} catch (Exception $error) {
    echo $error;

    readline();
}
