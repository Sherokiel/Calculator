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
    $info = $result . str_repeat(PHP_EOL, $emptyLinesCount);

    echo ($info);

    return $info;
}

function info_box(...$lines)
{
    $lineLengths = string_length($lines);

    $indent = 6;
    $length = max($lineLengths) + $indent;

    write_symbol_line($length, '*');

    foreach ($lines as $line) {
        $message = str_pad($line, $length - 6, ' ', STR_PAD_BOTH);

        info("*  {$message}  *", 1);
    }

    return write_symbol_line($length, '*');
}

function write_symbol_line($length, $symbol)
{
    return info(str_repeat($symbol, $length), 1);
}

function show_info_block()
{
    $info = INFO_BLOCK;

    $lineLengths = string_length($info);
    $length = max($lineLengths) + 19;

    write_symbol_line($length,'*');
    info(str_pad('Avaliable Commands in calculator', $length,' ',STR_PAD_BOTH),1);
    foreach ($info as $key => $value) {
        $b = strlen($value);
        $a = 50 - $b;
        echo str_pad($key, $a, '_') . "{$value}" . PHP_EOL;
    }

    return write_symbol_line($length,'*');
}
