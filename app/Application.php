<?php

class Application
{
    protected $historyRepository;

    function __construct()
    {
        $this->historyRepository = new HistoryRepository();
    }

    public function run()
    {

        $times = 0;
        $date = date('d-m-Y');
        info_box('', 'Welcome to the calculator app!', '', 'Print "help" to learn more about the app.', '');
        while ($times < 1) {

            $command = choose('Enter command: ', AVAILABLE_COMMANDS);

            if (in_array($command, SYSTEM_COMMANDS)) {
                $this->executeSystemCommand($command, $this->historyRepository);

                continue;
            }

            $argument1 = $this->readOperand('Enter fist number: ', $command);

            if ($command != '^' && $command != 'sr' ) {
                $argument2 = $this->readOperand('Enter second number: ', $command, true);
            } else {
                $argument2 = $this->readOperand('Enter exponent: ', $command);
            }

            $result = $this->calculate($argument1, $command, $argument2);

            info('Result: ' . $result);
            info('=====================');

            $this->historyRepository->create($date, $argument1, $argument2, $command, $result);
        }
    }
    public function calculate($argument1, $command, $argument2)
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

    protected function readOperand($message, $command, $isSecondOperand = false)
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

    protected function executeSystemCommand($command, $historyRepository)
    {
        switch($command) {
            case(QUIT):
                $this->finishApp();

                break;

            case(INFO):
                show_info_block('Avaliable Commands in calculator', INFO_BLOCK);

                break;

            case(HISTORY):
                $this->showHistory($historyRepository);

                break;

            case(EXPORT_HISTORY):
                $this->historyToTxt($historyRepository);
        }
    }

    protected function finishApp()
    {
        $command = choose('Are you sure to wanna quit? Yes/No ', [AGREE, DEGREE]);

        if ($command == AGREE) {
            exit();
        }
    }

    protected function showHistory($historyRepository)
    {
        $history = $historyRepository->all();

        if (empty($history)) {
            return info('You have no history');
        }

        do {
            $historyGroups = array_group($history, 'date');

            $historyCommands = array_merge(['full', 'help', 'back'], array_keys($historyGroups));
            $showDateHistory = ask('Enter date in format DD-MM-YYYY or "full" to show full history: ');
            $isDataValid = (is_date($showDateHistory) || in_array($showDateHistory, $historyCommands));

            if (!$isDataValid) {
                info('Please input a valid date in format DD-MM-YYYY (e.g. 25-12-2022).');

                continue;
            }

            $isDataValid = (in_array($showDateHistory, $historyCommands));

            if (!$isDataValid) {
                info('You have no history in that day.');

                continue;
            }

            if ($showDateHistory == 'help') {
                show_info_block('Available Commands in history viewer', HISTORY_VIEWER_COMMANDS, 19, 71);
                $isDataValid = false;

                continue;
            }

            if ($showDateHistory == 'full') {
                $this->showHistoryItems($historyGroups);
                $isDataValid = false;

                continue;
            }

            $isDataValid = (!array_key_exists($showDateHistory, $historyGroups));

            if (!$isDataValid) {
                return $this->showHistoryItems([$showDateHistory => $historyGroups[$showDateHistory]]);
            }
        } while (!$isDataValid);

        return info('You are returned to main menu');
    }

    protected function writeHistoryLine($historyItem)
    {
        $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);
        $prefix = ($isBasicMathOperation) ? '   ' : '(!)';

        return "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";
    }

    protected function showHistoryItems($historyGroups)
    {
        foreach ($historyGroups as $date => $historyItems) {
            info("{$date}: ");

            foreach ($historyItems as $historyItem) {
                $historyFunction = $this->writeHistoryLine($historyItem);

                info($historyFunction, 1);
            }
        }

        return write_symbol_line(15, '=');
    }

    protected function historyToTxt($historyRepository)
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
                $historyFunction = $this->writeHistoryLine($historyItem);

                file_put_contents($fullPathName, $historyFunction . PHP_EOL, FILE_APPEND);
            }
        }

        info('History saved!');
    }
}
