<?php

$DS = DIRECTORY_SEPARATOR;

require 'constants.php';
require "libraries{$DS}console_helpers.php";
require "libraries{$DS}helpers.php";
require "classes.php";
require "index.php";

$calculator = new Application;
$calculator->run();
