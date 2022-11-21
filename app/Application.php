<?php

$DS = DIRECTORY_SEPARATOR;

require 'constants.php';
require "libraries{$DS}console_helpers.php";
require "libraries{$DS}helpers.php";
require "app{$DS}HistoryRepository.php";

class Application
{
    protected $messages;
    protected $historyRepository;

    public function __construct()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->messages = file_get_contents("locale{$DS}en.json");
        $this->messages = json_decode($this->messages, true);

        $this->historyRepository = new HistoryRepository();
    }

    public function run()
    {
        $isRunning = true;
        info_box('', "{$this->messages['textWelcome1']}", '', "{$this->messages['textWelcome2']}", '');

        while ($isRunning) {
            $command = choose("{$this->messages['entCommand']}", AVAILABLE_COMMANDS);

            if (in_array($command, SYSTEM_COMMANDS)) {
                $this->executeSystemCommand($command);

                continue;
            }

            $argument1 = $this->readOperand("{$this->messages['textEntFirst']}", $command);

            $argument2 = ($command !== '^' && $command !== 'sr' )
                ? $this->readOperand("{$this->messages['textEntSecond']}", $command, true)
                : $this->readOperand("{$this->messages['textEntExponent']}", $command);

            $result = $this->calculate($argument1, $command, $argument2);

            info("{$this->messages['textResult']}" . $result);
            write_symbol_line(25, '=');

            $this->historyRepository->create(date('d-m-Y'), $argument1, $argument2, $command, $result);
        }
    }

    protected function calculate($argument1, $command, $argument2)
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
                $this->messages['textUndefinedCommand'] = str_replace('%', $command, $this->messages['textUndefinedCommand']);

                return info("{$this->messages['textUndefinedCommand']}");
        }
    }

    protected function readOperand($message, $command, $isSecondOperand = false)
    {
        do {
            $argument = readline($message);
            $int1 = str_to_number($argument);
            $isDataValid = ($argument == $int1);

            if (!$isDataValid) {
                info("{$this->messages['textIfLetter']}");

                continue;
            }

            $isDataValid = strlen($argument) > 0;
            if (!$isDataValid) {
                info("{$this->messages['textIfSpace']}");

                continue;
            }

            settype($argument, 'integer');

            if ($isSecondOperand) {
                $isDataValid = ($command !== '/' || $argument !== 0);

                if (!$isDataValid) {
                    info("{$this->messages['textIfSeparateZero']}");
                }
            }
        } while (!$isDataValid);

        return $argument;
    }

    protected function executeSystemCommand($command)
    {
        switch ($command) {
            case(INFO):
                return show_info_block("{$this->messages['textInfoBlock']}", INFO_BLOCK);
            case(HISTORY):
                return $this->showHistory();
            case(EXPORT_HISTORY):
                return $this->historyToTxt();
            case(QUIT):
                $this->finishApp();
            default:
                $this->messages['textUndefinedCommand'] = str_replace('%', $command, $this->messages['textUndefinedCommand']);

                return info("{$this->messages['textUndefinedCommand']}");
        }
    }

    protected function finishApp()
    {
        $command = choose("{$this->messages['textQuit']}", [AGREE, DEGREE]);

        if ($command == AGREE) {
            exit();
        }
    }

    protected function showHistory()
    {
        $history = $this->historyRepository->all();

        if (empty($history)) {
            return info("{$this->messages['textNoHistory']}");
        }

        do {
            $historyGroups = array_group($history, 'date');

            $historyCommands = array_merge(['full', 'help', 'back'], array_keys($historyGroups));
            $showDateHistory = ask("{$this->messages['textHistory']}");
            $isDataValid = (is_date($showDateHistory) || in_array($showDateHistory, $historyCommands));

            if (!$isDataValid) {
                info("{$this->messages['textHistoryWrongInput']}");

                continue;
            }

            $isDataValid = in_array($showDateHistory, $historyCommands);

            if (!$isDataValid) {
                info("{$this->messages['textNoHistoryDay']}");

                continue;
            }

            if ($showDateHistory === 'help') {
                show_info_block("{$this->messages['textHistoryHelp']}", HISTORY_VIEWER_COMMANDS, 19, 71);
                $isDataValid = false;

                continue;
            }

            if ($showDateHistory === 'full') {
                $this->showHistoryItems($historyGroups);
                $isDataValid = false;

                continue;
            }

            $isDataValid = (!array_key_exists($showDateHistory, $historyGroups));

            if (!$isDataValid) {
                return $this->showHistoryItems([$showDateHistory => $historyGroups[$showDateHistory]]);
            }
        } while (!$isDataValid);

        return info("{$this->messages['textHistoryBack']}");
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

    protected function historyToTxt()
    {
        $historyGroups = array_group($this->historyRepository->all(), 'date');
        $nameOfFile = readline("{$this->messages['textNameOfFileCreate']}");
        $pathToFile = readline("{$this->messages['textNameOfDirectoryCreate']}");
        $fullPathName = "{$pathToFile}{$nameOfFile}.txt";

        if (file_exists($fullPathName)) {
            $this->messages['fileExist'] = str_replace("%", $fullPathName,  $this->messages['fileExist']);

            $command = choose("{$this->messages['fileExist']}", [AGREE, DEGREE]);

            switch ($command) {
                case (AGREE):
                    return file_put_contents($fullPathName, '');
                case (DEGREE):
                    return PHP_EOL;
            }
        }

        foreach ($historyGroups as $date => $historyItems) {
            file_put_contents($fullPathName, $date . PHP_EOL, FILE_APPEND);

            foreach ($historyItems as $historyItem) {
                $historyFunction = $this->writeHistoryLine($historyItem);

                file_put_contents($fullPathName, $historyFunction . PHP_EOL, FILE_APPEND);
            }
        }

        return info("{$this->messages['textHistorySaved']}");
    }
}
