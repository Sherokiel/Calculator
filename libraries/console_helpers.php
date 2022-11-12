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
    foreach ($lines as $maxLines) {
        $h1 = strlen($maxLines);
        $lineValue[] = $h1;
    }

    $length = max($lineValue) + 6;

    info(str_repeat('*', $length), 1);;

    foreach ($lines as $msg) {
        $message = str_pad($msg, $length - 6, ' ', STR_PAD_BOTH);

        info("*  {$message}  *", 1);
    }
    info(str_pad('*', $length, '*'), 1);
}
