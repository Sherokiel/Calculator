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

    return $result;
}

function info_box(...$lines)
{
    $lineLengths = array_map(function ($line) {
        return strlen($line);
    }, $lines);

    $indent = 6;
    $length = max($lineLengths) + $indent;

    write_symbol_line($length, '*');

    foreach ($lines as $line) {
        $message = str_pad($line, $length - 6, ' ', STR_PAD_BOTH);

        info("*  {$message}  *", 1);
    }

    write_symbol_line($length, '*');
}

function write_symbol_line($length, $symbol)
{
    info(str_repeat($symbol, $length), 1);
}