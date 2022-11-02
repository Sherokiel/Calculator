<?php

$times = 0;
$history = [];

const QUIT = 'exit';
const INFO = 'help';
const AGREE = 'yes';
const DEGREE = 'no';
const HISTORY = 'history';

const AVAILABLE_COMMANDS = ['+', '-', '*', '/', '^', 'sr', QUIT, INFO, HISTORY];

while ($times < 1) {
    info('Enter ' . INFO . ' to see available commands.');

    $command = choose('Enter command: ', AVAILABLE_COMMANDS);

    $isSystemCommand = in_array($command, [HISTORY, INFO, QUIT]);

    if ($isSystemCommand) {
        executeSystemCommand($command);

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

    $history = "{$argument1} {$command} {$argument2}  =  {$result}" . PHP_EOL;

    file_put_contents('history.txt', $history, FILE_APPEND);
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

function str_to_number($string)
{
    $isNegative = str_starts_with($string,'-');
    $string = str_replace(['+', '-'], ' ', $string);

    if ($isNegative) {
        $string = '-' . $string;
    }

    return (string) filter_var($string, FILTER_SANITIZE_NUMBER_INT);
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

        if ($isSecondOperand){
            $isDataValid = ($command != '/' || $argument != 0);

            if (!$isDataValid) {
                info('Cant separate on zero.');
            }
        }

    } while (!$isDataValid);

    return $argument;
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

function info($result)
{
    echo $result . PHP_EOL . PHP_EOL;
}

function executeSystemCommand($command)
{
    switch($command) {
        case(QUIT):
            finish_app();

            break;

        case(INFO):
            show_info_block();

            break;

        case(HISTORY):
            show_history();
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
    info((implode(' ;  ', AVAILABLE_COMMANDS)) . ' ;');
}

Function show_history()
{
    $history = file_get_contents('history.txt');
    info($history);
}
