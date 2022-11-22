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
        return mb_strlen($strings);
    }, $strings);
}

function prepareFilePath($path)
{
    return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
}

function mb_str_pad($text, $length, $filling = ' ', $padType = STR_PAD_BOTH)
{
    $lengthDifferent = strlen($text) - mb_strlen($text);

    return str_pad($text, $length + $lengthDifferent, $filling , $padType);
}
