<?php

require 'libraries' . DIRECTORY_SEPARATOR . 'helpers.php';
require prepare_file_path('app/Application.php');

$calculator = new Application;
$calculator->run();
