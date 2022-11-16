<?php

$DS = DIRECTORY_SEPARATOR;

require 'constants.php';
require "libraries{$DS}console_helpers.php";
require "libraries{$DS}helpers.php";
require "classes.php";

$historyRepository = new HistoryRepository('data_storage', 'history.json');
$date = date('d-m-Y');
$times = 0;

info_box('', 'Welcome to the calculator app!', '', 'Print "help" to learn more about the app.', '');

while ($times < 1) {

    $command = choose('Enter command: ', AVAILABLE_COMMANDS);

    if (in_array($command, SYSTEM_COMMANDS)) {
        execute_system_command($command, $historyRepository);

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

    $historyRepository->create($date, $argument1, $argument2, $command, $result);
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

function execute_system_command($command, $historyRepository)
{
    switch($command) {
        case(QUIT):
            finish_app();

            break;

        case(INFO):
            show_info_block();

            break;

        case(HISTORY):
            show_history($historyRepository);

            break;

        case(EXPORT_HISTORY):
            history_to_txt($historyRepository);
    }
}

function finish_app()
{
    $command = choose('Are you sure to wanna quit? Yes/No ', [AGREE, DEGREE]);

    if ($command == AGREE) {

        exit();
    }
}

function show_info_block()
{
    info('Calculator commands: ' . (implode(' ; ', AVAILABLE_COMMANDS)) . ' ;');
    info('History commands:' . PHP_EOL . 'Full - to see full history.' . PHP_EOL . 'Format of date "01-01-1990" - to show history of current date.');
}

function show_history($historyRepository)
{
    $history = $historyRepository->all();

    if (empty($history)) {
        return info('You have no history');
    }
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
        return show_history_items([$showDateHistory => $historyGroups[$showDateHistory]]);
    }

    return show_history_items($historyGroups);

}

function write_history_line($historyItem)
{
    $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);
    $prefix = ($isBasicMathOperation) ? '   ' : '(!)';
    return "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";
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

    return write_symbol_line(15, '=');
}

function history_to_txt($historyRepository)
{
    $historyGroups = array_group($historyRepository->all(), 'date');
    $nameOfFile = readline('Enter name of created file: ');
    $pathToFile = readline('Enter path of save exported history: ');
    $fullPathName = "{$pathToFile}{$nameOfFile}.txt";

    if (file_exists($fullPathName)) {
        $command = choose("File {$fullPathName} already exists, do you want to replace it? Yes/no: ", [AGREE, DEGREE]);

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