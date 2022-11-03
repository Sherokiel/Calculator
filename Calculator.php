<?php
require 'constants.php';
require 'libraries\console_helpers.php';

fopen('history.json', 'a');

$times = 0;


while ($times < 1) {
    $history = file_get_contents('history.json');

    json_decode($history);

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

    $history1 = array("{$argument1} {$command} {$argument2}  =  {$result}" . PHP_EOL);

    json_encode($history1);

    file_put_contents('history.json', $history1, FILE_APPEND);

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

        if ($isSecondOperand){
            $isDataValid = ($command != '/' || $argument != 0);

            if (!$isDataValid) {
                info('Cant separate on zero.');
            }
        }

    } while (!$isDataValid);

    return $argument;
}

function execute_system_command($command,$history)
{
    switch($command) {
        case(QUIT):
            finish_app();

            break;

        case(INFO):
            show_info_block();

            break;

        case(HISTORY):
            show_history($history);
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

Function show_history($history)
{
    if($history == null) {
        info('You have no history');
    }

    info($history);
}
