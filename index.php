<?php

$DS = DIRECTORY_SEPARATOR;

require 'constants.php';
require "libraries{$DS}console_helpers.php";
require "libraries{$DS}helpers.php";
require "app{$DS}HistoryRepository.php";
require "app{$DS}Application.php";

$calculator = new Application;
$calculator->run();
