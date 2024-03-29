<?php

namespace App;

use App\Exceptions\OperandException;
use App\Repositories\HistoryRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\UserRepository;
use App\Exporters\HistoryConsoleExporter;
use App\Exporters\HistoryTxtExporter;
use App\Services\CalculatorService;
use App\Services\HistoryService;

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

        $this->calculatorService = new CalculatorService();
        $this->historyService = new HistoryService();

        $this->userRepository = new UserRepository();
        $this->historyRepository = new HistoryRepository();

        $this->historyConsoleExporter = new HistoryConsoleExporter();
        $this->historyTxtExporter = new HistoryTxtExporter();
    }

    public function run()
    {
        $isRunning = true;

        $user = $this->authorize();

        info_box(
            $this->getText('info', 'welcome_user', ['user' => $user]),
            $this->messages['info']['welcome1'],
            '',
            $this->getText('info', 'welcome2', ['info' => INFO]),
            ''
        );

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

            $result = $this->calculatorService->calculate($argument1, $command, $argument2);

            info($this->messages['info']['result'] . $result);
            write_symbol_line(25, '=');

            $this->historyService->create($argument1, $argument2, $command, $result);
        }
    }

    protected function readOperand($message, $command, $isSecondOperand = false)
    {
        $argument = readline($message);

        try {
            return $this->calculatorService->formatOperand($argument, $command, $isSecondOperand);
        } catch (OperandException $error) {
            info($error->getMessage());

            return $this->readOperand($message, $command, $isSecondOperand = false);
        }
    }

    protected function executeSystemCommand($command)
    {
        switch ($command) {
            case(INFO):
                show_info_block($this->messages['info']['info_block'], $this->getCommandList());

                break;
            case(HISTORY):
                $this->showHistory();

                break;
            case(CHOICE_LANGUAGE):
                $this->choiceLocale();

                break;
            case(QUIT):
                $this->finishApp();
            default:
                info($this->getText('errors', 'undefined_command', ['command' => $command]));
        }
    }

    protected function finishApp()
    {
        $command = choice($this->getText('questions', 'quit', ['yes' => AGREE, 'no' => DEGREE]), [AGREE, DEGREE], $this->getText('errors', 'choice_error', ['info' => INFO]));

        if ($command == AGREE) {
            exit();
        }
    }

    protected function showHistory():void
    {
        if (!$this->historyRepository->isExist()) {
            info($this->messages['info']['no_history']);
        }

        $output = choice($this->getText('questions', 'export_question', ['export' => EXPORT_HISTORY, 'screen' => SCREEN]), [EXPORT_HISTORY, SCREEN]);
        $fullPathName = null;

        if ($output === EXPORT_HISTORY) {
            $defaultFileName = 'export_' . now();
            $nameOfFile = readline($this->getText('info', 'name_of_file_create', ['defaultPath' => $defaultFileName]));
            $pathToFile = readline($this->messages['info']['name_of_directory_create']);

            if (empty($nameOfFile)) {
                $nameOfFile = $defaultFileName;
            }

            $fullPathName = "{$pathToFile}{$nameOfFile}";

            $ext = pathinfo($fullPathName, PATHINFO_EXTENSION);

            if (empty($ext)) {
                $fullPathName .= '.txt';
            } elseif ($ext !== 'txt') {
                info($this->messages['errors']['wrng_ext']);
            }

            if (file_exists($fullPathName)) {
                $command = choice($this->getText('questions', 'text_file_exist', ['filepath' => $fullPathName, 'yes' => AGREE, 'no' => DEGREE]), [AGREE, DEGREE]);

                switch ($command) {
                    case (AGREE):
                        file_put_contents($fullPathName, '');

                        break;
                    case (DEGREE):
                        break;
                }
            }
        }

        do {
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
                info($this->messages['info']['history_back']);
            }


            if ($showDateHistory === FULL) {
                $showDateHistory = null;
            } elseif (!$this->historyRepository->isExist(['date' => $showDateHistory])) {
                info($this->messages['info']['no_history_day']);

                $isDataValid = false;
            }
        } while (!$isDataValid);

        $this->historyService->export($output, $showDateHistory, $fullPathName);

        if ($output === EXPORT_HISTORY) {
            info($this->getText('info','history_saved', ['filepath' => $fullPathName]));
        }
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

        clear_screen();

        return $this->messages = $this->loadLocale($lang);
    }

    protected function authorize()
    {
        do {
            $userName = readline($this->messages['info']['enter_user']);
            $user = $this->userRepository->first(['user_name' => $userName]);

            if (empty($user)) {
                do {
                    $password = readline($this->messages['info']['create_pass']);
                    $passwordConfirm = readline($this->messages['info']['confirm_pass']);

                    if ($password !== $passwordConfirm) {
                        info($this->messages['errors']['not_match']);

                        continue;
                    }

                    $user = $this->userRepository->create([
                        'user_name' => $userName,
                        'password' => $password,
                        'role' => UserRepository::ROLE_BASIC
                    ]);

                    info($this->messages['info']['reg_in']);
                } while ($password !== $passwordConfirm);
            } else {
                $password = readline($this->messages['info']['enter_pass']);
            }

            if ($user['password'] !== $password) {
                info($this->messages['errors']['wrong_pass']);
            }
        } while ($user['password'] !== $password);

        $this->historyService->setUser($user);

        info($this->messages['info']['logged_in']);

        return $user['user_name'];
    }

    protected function getCommandList()
    {
        return [
            '+' => $this->messages['commands_descriptions']['addition'],
            '-' => $this->messages['commands_descriptions']['subtraction'],
            '*' => $this->messages['commands_descriptions']['multiplication'],
            '/' => $this->messages['commands_descriptions']['division'],
            '^' => $this->messages['commands_descriptions']['exponentiation'],
            'sq' => $this->messages['commands_descriptions']['sq'],
            QUIT => $this->messages['commands_descriptions']['quit'],
            HISTORY => $this->messages['commands_descriptions']['history'],
            CHOICE_LANGUAGE => $this->messages['commands_descriptions']['choice_language']
        ];
    }
}