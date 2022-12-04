<?php

use App\Application;

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';

require prepare_file_path('app/Application.php');
require prepare_file_path('app/HistoryConsoleExporter.php');
require prepare_file_path('app/HistoryTxtExporter.php');
require prepare_file_path('app/DataExporter.php');
require prepare_file_path('app/Repositories/FileBaseRepository.php');
require prepare_file_path('app/Repositories/IniBaseRepository.php');
require prepare_file_path('app/Repositories/JsonBaseRepository.php');
require prepare_file_path('app/Repositories/HistoryRepository.php');
require prepare_file_path('app/Repositories/SettingsRepository.php');
require prepare_file_path('libraries/console_helpers.php');
require prepare_file_path('constants.php');

try {
    $calculator = new Application();
    $calculator->run();
} catch (Error $error) {
    echo $error;

    readline();
}
