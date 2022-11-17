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
        $command = ask($message);
        $isDataValid = in_array($command, $availableValues);

        if (!$isDataValid) {
            info('Wrong command, enter ' . INFO . ' to see commands.');
        }
    } while (!$isDataValid);

    return $command;
}

function info($result, $emptyLinesCount = 2)
{
    $info = $result . str_repeat(PHP_EOL, $emptyLinesCount);

    echo ($info);

    return $info;
}

function info_box(...$lines)
{
    $maxLineLengths = max(string_lengths($lines));

    $indent = 6;
    $length = $maxLineLengths + $indent;

    write_symbol_line($length, '*');

    foreach ($lines as $line) {
        $message = str_pad($line, $length - 6, ' ', STR_PAD_BOTH);

        info("*  {$message}  *", 1);
    }

    return write_symbol_line($length, '*');
}

function write_symbol_line($length, $symbol, $emptyLinesCount = 2)
{
    return info(str_repeat($symbol, $length), $emptyLinesCount);
}

/**
 * @param array $data associative array of strings, keys as items and values as item description
 */
function show_info_block($title, $info, $widthOfBox = 19, $lineWidthRatio = 48)
{
    $maxLineLengths = max(string_lengths($info));
    $length = $maxLineLengths + $widthOfBox;

    write_symbol_line($length, '*', 1);
    info(str_pad($title, $length, ' ', STR_PAD_BOTH), 1);

    foreach ($info as $key => $value) {
        $separatorLength = $lineWidthRatio - strlen($value);

        info (str_pad($key, $separatorLength, '_') . $value, 1);
    }

    return write_symbol_line($length, '*', 1);
}

function ask($message)
{
    $result = readline($message);

    return strtolower($result);
}

function is_date($date, $format = 'j-m-Y')
{
    return date_create_from_format($format, $date);
}