<?php

function array_group($array, $key)
{
    $result = [];

    foreach ($array as $item) {
        $result[$item[$key]][] = $item;
    }

    return $result;
}

function string_lengths($strings)
{
    return array_map(function ($strings) {
        return strlen($strings);
    }, $strings);
}
