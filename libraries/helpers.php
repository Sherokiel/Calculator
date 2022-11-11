<?php

function array_group($array, $key)
{
    $result = [];

    foreach ($array as $item) {
        $result[$item[$key]][] = $item;
    }

    return $result;
}

function welcome($msg1,$msg2)
{
    $length = 65;
    $string = 0;
    $maxStrings = 14;

    while ($string<=$maxStrings) {

        if ($string < 1 || $string > $maxStrings - 1) {
            $i = 0;

            while ($i < $length) {
                echo "*";
                $i++;
            }
            echo PHP_EOL;

        } else {
            switch ($string)
            {
                case ($string == round($maxStrings / 3)):
                    $msg = $msg1;
                    break;
                case ($string == round($maxStrings / 2)):
                    $msg = $msg2;
                    break;
                default:
                    $msg = '';
            }
            echo "*";
            echo str_pad($msg, $length - 2, ' ', STR_PAD_BOTH);
            echo "*" . PHP_EOL;
        }
        $string++;
    }
}
