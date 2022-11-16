<?php

function array_group($array, $key)
{
    $result = [];

    foreach ($array as $item) {
        $result[$item[$key]][] = $item;
    }

    return $result;
}

function string_length($string)
{
    return array_map(function ($string) {
        return strlen($string);
    }, $string);
}
