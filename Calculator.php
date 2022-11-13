<?php

$DS = DIRECTORY_SEPARATOR;

require 'constants.php';
require "libraries{$DS}console_helpers.php";
require "libraries{$DS}helpers.php";

fopen('history.json', 'a+');

$date = date('d-m-Y');
$history = file_get_contents('history.json');
$times = 0;

if ($history === null) {
    $history = [];
} else {
    $history = json_decode($history, true);
}

info_box('', 'Welcome to the calculator app!', '', 'Print "help" to learn more about the app.', '');

while ($times < 1) {

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
    switch ($command) {
        case '+':
            return $argument1 + $argument2;
        case '-':
            return $argument1 - $argument2;
        case '*':
            return $argument1 * $argument2;
        case '/':
            return $argument1 / $argument2;
        case '^':
            return pow($argument1, $argument2);
        case 'sr':
            return pow($argument1, (1 / $argument2));
        default:
            return $command;
    }
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

        case(EXPORT_HISTORY):
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
    info('Calculator commands: ' . (implode(' ; ', AVAILABLE_COMMANDS)) . ' ;');
    info('History commands:' . PHP_EOL . 'Full - to see full history.' . PHP_EOL . 'Format of date "01-01-1990" - to show history of current date.');
}

function show_history($history)
{
    if ($history === null) {
        info('You have no history');
    } else {
        $historyGroups = array_group($history, 'date');
        $historyCommands = ['full', 'help'];

        $historyCommands = array_merge($historyCommands, array_keys($historyGroups));
        $showDateHistory = readline('Enter date of history (DD-MM-YYYY), "Full" or "Help"  : ');

        $isDate = date_create_from_format('j-m-Y', $showDateHistory);

        if ($isDate === false && !in_array($showDateHistory, $historyCommands)) {
            return info('Please input a valid date in format DD-MM-YYYY (e.g. 25-12-2022).');
        }

        if ($showDateHistory == 'help') {
            show_info_block();
        }

        info('', 1);

        if (!in_array($showDateHistory, $historyCommands)) {
            return info('You have no history in that day.');
        }

        if (array_key_exists($showDateHistory, $historyGroups)) {
            show_history_items([$showDateHistory => $historyGroups[$showDateHistory]]);
        } elseif ($showDateHistory == 'full') {
            show_history_items($historyGroups);
        }
    }

   return $history;
}

function write_history_line($historyItem)
{
    $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);
    $prefix = ($isBasicMathOperation) ? '   ' : '(!)';
    $historyFunction = "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";

    return $historyFunction;
}

function show_history_items($historyGroups)
{
    foreach ($historyGroups as $date => $historyItems) {
        info("{$date}: ");

        foreach ($historyItems as $historyItem) {
            $historyFunction = write_history_line($historyItem);

            info($historyFunction, 1);
        }
    }

    write_symbol_line(15, '=');
}

function history_to_txt($history)
{
    $historyGroups = array_group($history, 'date');
    $nameOfFile = readline('Enter name of created file: ');
    $pathToFile = readline('Enter path of save exported history: ');
    $fullPathName = "{$pathToFile}{$nameOfFile}.txt";

    if (file_exists($fullPathName)) {
        $command = choose("File ${$fullPathName} already exists, do you want to replace it? Yes/no: ", [AGREE, DEGREE]);

        switch ($command) {
            case (AGREE):
                file_put_contents($fullPathName, '');

                break;

            case (DEGREE):
                echo PHP_EOL;

                return;
        }
    }

    foreach ($historyGroups as $date => $historyItems) {
        file_put_contents($fullPathName, $date . PHP_EOL, FILE_APPEND);

        foreach ($historyItems as $historyItem) {
            $historyFunction = write_history_line($historyItem);

            file_put_contents($fullPathName, $historyFunction . PHP_EOL, FILE_APPEND);
        }
    }

    info('History saved!');
}