<?php

namespace App;

use App\Repositories\HistoryRepository;
use App\Repositories\SettingsRepository;
use App\Exporters\HistoryExporter;

class Application
{
    protected $messages;
    protected $historyRepository;
    protected $settingsRepository;
    protected $historyExporter;

    public function __construct()
    {
        $this->settingsRepository = new SettingsRepository();

        $lang = $this->settingsRepository->getSetting('localization', 'locale');
        $this->messages = $this->loadLocale($lang);

        $this->historyRepository = new HistoryRepository();
        $this->historyExporter = new HistoryExporter();
    }

    public function run()
    {
        $isRunning = true;

        info_box('', $this->messages['info']['welcome1'], '', $this->getText('info', 'welcome2', INFO), '');

        while ($isRunning) {
            $command = choice($this->messages['info']['enter_command'], AVAILABLE_COMMANDS, $this->getText('errors', 'choice_error', INFO));

            if (in_array($command, SYSTEM_COMMANDS)) {
                $this->executeSystemCommand($command);

                continue;
            }

            $argument1 = $this->readOperand($this->messages['info']['enter_first'], $command);

            $argument2 = ($command !== '^' && $command !== 'sr' )
                ? $this->readOperand($this->messages['info']['enter_second'], $command, true)
                : $this->readOperand($this->messages['info']['enter_exponent'], $command);

            $result = $this->calculate($argument1, $command, $argument2);

            info($this->messages['info']['result'] . $result);
            write_symbol_line(25, '=');

            $this->historyRepository->create([
                'date' => date('d-m-Y'),
                'first_operand' => $argument1,
                'second_operand' => $argument2,
                'sign' => $command,
                'result' => $result,
            ]);
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
                return info($this->getText('errors', 'undefined_command', $command));
        }
    }

    protected function readOperand($message, $command, $isSecondOperand = false)
    {
        do {
            $argument = readline($message);
            $int1 = str_to_number($argument);
            $isDataValid = ($argument == $int1);

            if (!$isDataValid) {
                info($this->messages['errors']['if_letter']);

                continue;
            }

            $isDataValid = strlen($argument) > 0;
            if (!$isDataValid) {
                info($this->messages['errors']['if_space']);

                continue;
            }

            settype($argument, 'integer');

            if ($isSecondOperand) {
                $isDataValid = ($command !== '/' || $argument !== 0);

                if (!$isDataValid) {
                    info($this->messages['errors']['if_separate_zero']);
                }
            }
        } while (!$isDataValid);

        return $argument;
    }

    protected function executeSystemCommand($command)
    {
        switch ($command) {
            case(INFO):
                return show_info_block($this->messages['info']['info_block'], INFO_BLOCK);
            case(HISTORY):
                return $this->showHistory();
            case(EXPORT_HISTORY):
                return $this->historyToTxt();
            case(CHOICE_LANGUAGE):
                return $this->choiceLocale();
            case(QUIT):
                $this->finishApp();
            default:
                return info($this->getText('errors', 'undefined_command', $command));
        }
    }

    protected function finishApp()
    {
        $command = choice($this->getText('questions', 'quit', AGREE . ' or ' . DEGREE), [AGREE, DEGREE], $this->getText('errors', 'choice_error', INFO));

        if ($command == AGREE) {
            exit();
        }
    }

    protected function showHistory()
    {
        $history = $this->historyRepository->all();

        if (empty($history)) {
            return info($this->messages['info']['no_history']);
        }

        do {
            $showDateHistory = ask($this->getText('info', 'info_history', FULL));
            $isDataExist = $this->historyRepository->isExist(['date' => $showDateHistory]);

            $isDataValid = (is_date($showDateHistory) || in_array($showDateHistory, HISTORY_COMMANDS) || $isDataExist);

            if (!$isDataValid) {
                info($this->messages['errors']['history_wrong_input']);

                continue;
            }

            $isDataValid = $isDataExist || in_array($showDateHistory, HISTORY_COMMANDS);

            if (!$isDataValid) {
                info($this->messages['info']['no_history_day']);

                continue;
            }

            if ($showDateHistory === 'help') {
                show_info_block($this->messages['info']['history_help'], HISTORY_VIEWER_COMMANDS, 19, 71);
                $isDataValid = false;

                continue;
            }

            if ($showDateHistory === FULL) {
                $showDateHistory = null;
            }

            $isDataValid = (!$isDataExist && !is_null($showDateHistory));

            if (!$isDataValid) {
                return $this->historyExporter->export($showDateHistory);
            }

        } while (!$isDataValid);

        return info($this->messages['info']['history_back']);
    }

    protected function writeHistoryLine($historyItem)
    {
        $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);
        $prefix = ($isBasicMathOperation) ? '   ' : '(!)';

        return "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";
    }

    protected function historyToTxt()
    {
        $historyGroups = array_group($this->historyRepository->all(), 'date');
        $nameOfFile = readline("{$this->messages['info']['name_of_file_create']}");
        $pathToFile = readline("{$this->messages['info']['name_of_directory_create']}");
        $fullPathName = "{$pathToFile}{$nameOfFile}.txt";

        if (file_exists($fullPathName)) {
            $command = choice($this->getText('questions', 'text_file_exist', $fullPathName), [AGREE, DEGREE]);

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

        return info($this->messages['info']['textHistorySaved']);
    }

    protected function loadLocale($lang)
    {
        $messages = file_get_contents(prepare_file_path("locale/{$lang}.json"));

        return json_decode($messages, true);
    }

    protected function getText($typeOfText, $text, $replacements)
    {
        return str_replace('%', $replacements, $this->messages[$typeOfText][$text]);
    }

    protected function choiceLocale()
    {
        $message = ($this->getText('info', 'select_lang', RUS . ' or ' . ENG));
        $errorMessage = $this->getText('errors', 'choice_error', INFO);
        $lang = choice($message , LANGUAGE , $errorMessage);
        $this->settingsRepository->setSetting($lang, 'localization', 'locale');

        popen('cls', 'w');

        return $this->messages = $this->loadLocale($lang);
    }
}
