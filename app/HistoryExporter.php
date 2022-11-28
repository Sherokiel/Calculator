<?php

namespace App\Exporters;

use App\Repositories\HistoryRepository;
use App\Repositories\SettingsRepository;

class HistoryExporter
{
    protected $data;
    protected $messages;
    protected $lang;
    protected $settingsRepository;
    protected $historyRepository;

    public function __construct()
    {
        $this->historyRepository = new HistoryRepository();
        $this->data = array_group($this->historyRepository->all(), 'date');

        $this->settingsRepository = new SettingsRepository();
        $this->lang = $this->settingsRepository->getSetting('localization', 'locale');
        $this->messages = $this->loadLocale($this->lang);
    }

    public function export($date = null)
    {
        if (empty($this->data)) {
            return info($this->messages['info']['no_history']);
        }

        if ($date === null) {
            return $this->showHistoryItems($this->data);
        }

        return $this->showHistoryItems([$date => $this->data[$date]]);
    }

    protected function writeHistoryLine($historyItem)
    {
        $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);
        $prefix = ($isBasicMathOperation) ? '   ' : '(!)';

        return "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";
    }

    protected function showHistoryItems($data)
    {
        foreach ($data as $date => $historyItems) {
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