<?php

namespace App;

use App\Repositories\HistoryRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\UserRepository;
use App\Exporters\HistoryConsoleExporter;
use App\Exporters\HistoryTxtExporter;

class Application
{
    protected $messages;
    protected $historyRepository;
    protected $settingsRepository;
    protected $historyConsoleExporter;
    protected $historyTxtExporter;
    protected $userRepository;

    public function __construct()
    {
        $this->settingsRepository = new SettingsRepository();
        $lang = $this->settingsRepository->getSetting('localization', 'locale');
        $this->messages = $this->loadLocale($lang);

        $this->userRepository = new UserRepository();

        $this->historyRepository = new HistoryRepository();
        $this->historyConsoleExporter = new HistoryConsoleExporter();
        $this->historyTxtExporter = new HistoryTxtExporter();
    }

    public function run()
    {
        $isRunning = true;

        $userName = $this->authorize();

        info_box(
            $this->getText('info', 'welcome_user', ['user' => $userName]),
            $this->messages['info']['welcome1'],
            '',
            $this->getText('info', 'welcome2', ['info' => INFO]),
            '');

        while ($isRunning) {
            $command = choice($this->messages['info']['enter_command'], AVAILABLE_COMMANDS, $this->getText('errors', 'choice_error', ['info' => INFO]));

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
                'date' => now(),
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
                return info($this->getText('errors', 'undefined_command', ['command' => $command]));
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
            case(CHOICE_LANGUAGE):
                return $this->choiceLocale();
            case(QUIT):
                $this->finishApp();
            default:
                return info($this->getText('errors', 'undefined_command', ['command' => $command]));
        }
    }

    protected function finishApp()
    {
        $command = choice($this->getText('questions', 'quit', ['yes' => AGREE, 'no' => DEGREE]), [AGREE, DEGREE], $this->getText('errors', 'choice_error', ['info' => INFO]));

        if ($command == AGREE) {
            exit();
        }
    }

    protected function showHistory()
    {
        if (!$this->historyRepository->isExist()) {
            return info($this->messages['info']['no_history']);
        }

        do {
            $output = choice($this->getText('questions', 'export_question', ['export' => EXPORT_HISTORY, 'screen' => SCREEN]), [EXPORT_HISTORY, SCREEN]);

            $exporter = $this->historyConsoleExporter;

            if ($output === 'export') {
                $defaultFileName = 'export_' . now();
                $nameOfFile = readline($this->getText('info', 'name_of_file_create', ['defaultPath' => $defaultFileName]));

                if (empty($nameOfFile)) {
                    $nameOfFile = $defaultFileName;
                }

                $pathToFile = readline($this->messages['info']['name_of_directory_create']);
                $fullPathName = "{$pathToFile}{$nameOfFile}";

                $ext = pathinfo($fullPathName, PATHINFO_EXTENSION);

                if (empty($ext)) {
                    $fullPathName .= '.txt';
                } elseif ($ext !== 'txt') {
                    return info($this->messages['errors']['wrng_ext']);
                }

                $this->historyTxtExporter->setFilePath($fullPathName);

                if (file_exists($fullPathName)) {
                    $command = choice($this->getText('questions', 'text_file_exist', ['filepath' => $fullPathName, 'yes' => AGREE, 'no' => DEGREE]), [AGREE, DEGREE]);

                    switch ($command) {
                        case (AGREE):
                            file_put_contents($fullPathName, '');

                            break;
                        case (DEGREE):
                            return '';
                    }
                }

                $exporter = $this->historyTxtExporter;
            }

            $showDateHistory = ask($this->getText('info', 'info_history', ['full' => FULL]));
            $isDataValid = (is_date($showDateHistory) || in_array($showDateHistory, HISTORY_COMMANDS));

            if (!$isDataValid) {
                info($this->messages['errors']['history_wrong_input']);

                continue;
            }

            if ($showDateHistory === 'help') {
                show_info_block($this->messages['info']['history_help'], HISTORY_VIEWER_COMMANDS, 19, 71);
                $isDataValid = false;

                continue;
            }

            if ($showDateHistory === 'back') {
                return info($this->messages['info']['history_back']);
            }

            if ($showDateHistory === FULL) {
                $showDateHistory = null;
            } elseif (!$this->historyRepository->isExist(['date' => $showDateHistory])) {
                info($this->messages['info']['no_history_day']);

                $isDataValid = false;
            }
        } while (!$isDataValid);

        if ($exporter instanceof HistoryTxtExporter) {
            info($this->getText('info','history_saved', ['filepath' => $fullPathName]));
        }

        return $exporter->export($showDateHistory);
    }

    protected function loadLocale($lang)
    {
        $messages = file_get_contents(prepare_file_path("locale/{$lang}.json"));

        return json_decode($messages, true);
    }

    protected function getText($typeOfText, $text, $replacements)
    {
        $message = $this->messages[$typeOfText][$text];

        foreach ($replacements as $key => $value) {
            $message = str_replace("%{$key}%", $value, $message);
        }

        return $message;
    }

    protected function choiceLocale()
    {
        $message = ($this->getText('info', 'select_lang', ['ru' => RUS, 'en' => ENG]));
        $errorMessage = $this->getText('errors', 'choice_error', ['info' => INFO]);
        $lang = choice($message , LANGUAGE , $errorMessage);
        $this->settingsRepository->setSetting($lang, 'localization', 'locale');

        popen('cls', 'w');

        return $this->messages = $this->loadLocale($lang);
    }

    protected function authorize()
    {
        do {
            $userName = readline($this->messages['info']['enter_user']);
            $user = $this->userRepository->first(['username' => $userName]);

            if (empty($user)) {
                do {
                    $password = readline($this->messages['info']['create_pass']);
                    $passwordConfirm = readline($this->messages['info']['confirm_pass']);

                    if ($password !== $passwordConfirm) {
                        info($this->messages['errors']['not_match']);

                        continue;
                    }

                    $user = $this->userRepository->create([
                        'username' => $userName,
                        'password' => $password
                    ]);

                    info($this->messages['info']['reg_in']);

                } while ($password !== $passwordConfirm);
            } else {
                $password = readline($this->messages['info']['enter_pass']);
            }

            if ($user['password'] !== $password) {
                info($this->getText('errors', 'not_found_user', ['username' => $userName]));
            }
        } while ($user['password'] !== $password);

        info($this->messages['info']['logged_in']);

        return $user['username'];
    }
}