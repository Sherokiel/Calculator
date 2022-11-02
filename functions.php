<?php
function str_to_number($string)
{
    $isNegative = str_starts_with($string,'-');
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

function info($result)
{
    echo $result . PHP_EOL . PHP_EOL;
}

function execute_system_command($command)
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
?>