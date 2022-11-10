<?php
function history_to_txt ()
{
    $value = file_get_contents('history.json');

    $exported = readline('Enter path of save exported history:');

    file_put_contents("{$exported}history.txt", $value);
    info('History saved!');

}

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
