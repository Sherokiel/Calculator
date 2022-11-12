<?php

function str_to_number($string)
{
    $isNegative = str_starts_with($string, '-');
    $string = str_replace(['+', '-'], ' ', $string);

    if ($isNegative) {
        $string = '-' . $string;
    }

    return (string) filter_var($string, FILTER_SANITIZE_NUMBER_INT);
}

function choose($message, $availableValues)
{
    do {
        $command = readline($message);
        $command = strtolower($command);
        $isDataValid = in_array($command, $availableValues);

        if (!$isDataValid) {
            info('Wrong command, enter ' . INFO . ' to see commands.');
        }
    } while (!$isDataValid);

    return $command;
}

function info($result, $emptyLinesCount = 2)
{
    echo $result . str_repeat(PHP_EOL, $emptyLinesCount);
}

function info_box(...$lines)
{
    $lineLengths = array_map(function ($maxLines) {
        return strlen($maxLines);
    }, $lines);

    $length = max($lineLengths) + 6;

    write_symbol_line($length, '*');

    foreach ($lines as $msg) {
        $message = str_pad($msg, $length - 6, ' ', STR_PAD_BOTH);

        info("*  {$message}  *", 1);
    }
    write_symbol_line($length, '*');
}

function write_symbol_line($length, $sybmol)
{
    info(str_repeat($sybmol, $length), 1);
}