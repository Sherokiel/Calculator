<?php

$DS = DIRECTORY_SEPARATOR;

require "libraries{$DS}helpers.php";
require prepareFilePath("app/Application.php");

$calculator = new Application;
$calculator->run();
