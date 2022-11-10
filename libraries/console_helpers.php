<?php
function history_to_txt ($history)
{

    $historyGroups = array_group($history, 'date');

    $pathToFile = readline('Enter path of save exported history:');

    $command = choose('Do u want to replace history? Yes/no: ',[AGREE, DEGREE]);

    if($command == AGREE) {
        file_put_contents("{$pathToFile}history.txt", ' ');
    }

    foreach ($historyGroups as $date => $historyItems) {
        file_put_contents("{$pathToFile}history.txt", $date . PHP_EOL, FILE_APPEND);

        foreach ($historyItems as $historyItem) {
            $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);

            $prefix = ($isBasicMathOperation) ? '   ' : '(!)';

            $historyFunction = "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";
            file_put_contents("{$pathToFile}history.txt", $historyFunction . PHP_EOL, FILE_APPEND);
        }
    }
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
