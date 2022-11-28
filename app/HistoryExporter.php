<?php

namespace App\Exporter;

use App\Repositories\HistoryRepository;
use App\Repositories\SettingsRepository;

class HistoryExporter
{
    protected $data;
    protected $messages;
    protected $lang;
    protected $settingsRepository;
    protected $jsonBaseRepository;

    public function __construct()
    {
        $this->jsonBaseRepository = new HistoryRepository();
        $this->data = $this->jsonBaseRepository->all();

        $this->settingsRepository = new SettingsRepository();
        $this->lang = $this->settingsRepository->getSetting('localization', 'locale');
        $this->messages = $this->loadLocale($this->lang);
    }

    public function export()
    {
        if (empty($this->data)) {
            return info($this->messages['info']['no_history']);
        }

        do {
            $historyGroups = array_group($this->data, 'date');
            $historyCommands = array_merge([FULL, 'help', 'back'], array_keys($historyGroups));
            $showDateHistory = ask($this->getText('info', 'info_history', FULL));
            $isDataValid = (is_date($showDateHistory) || in_array($showDateHistory, $historyCommands));

            if (!$isDataValid) {
                info($this->messages['errors']['history_wrong_input']);

                continue;
            }

            $isDataValid = in_array($showDateHistory, $historyCommands);

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
                $this->showHistoryItems($historyGroups);
                $isDataValid = false;

                continue;
            }

            $isDataValid = (!array_key_exists($showDateHistory, $historyGroups));

            if (!$isDataValid) {
                return $this->showHistoryItems([$showDateHistory => $historyGroups[$showDateHistory]]);
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
    protected function loadLocale($lang)
    {
        $this->messages = file_get_contents(prepare_file_path("locale/{$lang}.json"));

        return json_decode($this->messages, true);
    }

    protected function getText($typeOfText, $text, $replacements)
    {
        return str_replace('%', $replacements, $this->messages[$typeOfText][$text]);
    }
}