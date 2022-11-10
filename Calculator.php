<?php
require 'constants.php';
require 'libraries\console_helpers.php';
require 'libraries\helpers.php';

fopen('history.json', 'a+');

$date = date('d-m-Y');
$history = file_get_contents('history.json');
$times = 0;

if ($history === null) {
    $history = [];
} else {
    $history = json_decode($history, true);
}

while ($times < 1) {
    info('Enter ' . INFO . ' to see available commands.');

    $command = choose('Enter command: ', AVAILABLE_COMMANDS);

    if (in_array($command, SYSTEM_COMMANDS)) {
        execute_system_command($command, $history);

        continue;
    }

    $argument1 = read_operand('Enter fist number: ', $command);

    if ($command != '^' && $command != 'sr' ) {
        $argument2 = read_operand('Enter second number: ', $command, true);
    } else {
        $argument2 = read_operand('Enter exponent: ', $command);
    }

    $result = calculate($argument1, $command, $argument2);

    info('Result: ' . $result);
    info('=====================');

    $history[] = [
        'date' => $date,
        'first_operand' => $argument1,
        'second_operand' => $argument2,
        'sign' => $command,
        'result' => $result
    ];
}

function calculate($argument1, $command, $argument2)
{
    switch ($command)
    {
        case '+':
            $result = $argument1 + $argument2;
            break;
        case '-':
            $result = $argument1 - $argument2;
            break;
        case '*':
            $result = $argument1 * $argument2;
            break;
        case '/':
            $result = $argument1 / $argument2;
            break;
        case '^':
            $result = pow($argument1, $argument2);
            break;
        case 'sr':
            $result = pow($argument1, (1 / $argument2));
    }

    return $result;
}

function read_operand($message, $command, $isSecondOperand = false)
{
    do {
        $argument = readline($message);
        $int1 = str_to_number($argument);
        $isDataValid = ($argument == $int1);

        if (!$isDataValid) {
            info('Cant write letters.');

            continue;
        }

        $isDataValid = ($argument != null);

        if (!$isDataValid) {
            info('Cant write space.');

            continue;
        }

        if ($isSecondOperand) {
            $isDataValid = ($command != '/' || $argument != 0);

            if (!$isDataValid) {
                info('Cant separate on zero.');
            }
        }

    } while (!$isDataValid);

    return $argument;
}

function execute_system_command($command, $history)
{
    switch($command) {
        case(QUIT):
            finish_app($history);

            break;

        case(INFO):
            show_info_block();

            break;

        case(HISTORY):
            show_history($history);

            break;

        case(EXPORTHISTORY):
            history_to_txt($history);
    }
}

function finish_app($history)
{
    $command = choose('Are you sure to wanna quit? Yes/No ', [AGREE, DEGREE]);

    if ($command == AGREE) {
        if (!$history == NULL) {
            $history = json_encode($history);
            file_put_contents('history.json', $history);
        }

        exit();
    }
}

function show_info_block()
{
    info((implode(' ; ', AVAILABLE_COMMANDS)) . ' ;');
}

function show_history($history)
{
    if ($history === null) {
        info('You have no history');
    } else {
        $historyGroups = array_group($history, 'date');

        foreach ($historyGroups as $date => $historyItems) {
            info ("{$date}:");

            foreach ($historyItems as $historyItem) {
                $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);

                $prefix = ($isBasicMathOperation) ? '   ' : '(!)';

                $historyFunction = "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";

                info($historyFunction, 1);
            }

            info('=====================');
        }
    }
}

function history_to_txt($history)
{
    $historyGroups = array_group($history, 'date');
    $nameOfFile = readline('Enter name of created file: ' );
    $pathToFile = readline('Enter path of save exported history: ');
    $fullPathName = "{$pathToFile}{$nameOfFile}.txt";

    if (file_exists($fullPathName)) {

        $command = choose('Do u want to replace history? Yes/no: ', [AGREE, DEGREE]);

        switch ($command) {

            case (AGREE):
                file_put_contents($fullPathName, ' ');

                break;

            case (DEGREE):
                echo PHP_EOL;

                return;
        }
    }
    foreach ($historyGroups as $date => $historyItems) {
        file_put_contents($fullPathName, $date . PHP_EOL, FILE_APPEND);

        foreach ($historyItems as $historyItem) {
            $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);

            $prefix = ($isBasicMathOperation) ? '   ' : '(!)';

            $historyFunction = "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";
            file_put_contents($fullPathName, $historyFunction . PHP_EOL, FILE_APPEND);
        }
    }
    info('History saved!');
}