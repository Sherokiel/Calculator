<?php

$DS = DIRECTORY_SEPARATOR;
require "app{$DS}Application.php";

$calculator = new Application;
$calculator->run();
