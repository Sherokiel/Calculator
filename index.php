<?php

use App\Application;

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';

require prepare_file_path('app/Application.php');
require prepare_file_path('app/Repositories/Repository.php');
require prepare_file_path('app/Repositories/HistoryRepository.php');
require prepare_file_path('app/Repositories/SettingsRepository.php');

$calculator = new Application();
$calculator->run();
