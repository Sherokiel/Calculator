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

    public function export($exportedData)
    {
        if (empty($this->data)) {
            return info($this->messages['info']['no_history']);
        }

        return $this->showHistoryItems($exportedData);
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
        $messages = file_get_contents(prepare_file_path("locale/{$lang}.json"));

        return json_decode($messages, true);
    }
}