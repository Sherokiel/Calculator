<?php

namespace App\Exporters;

use App\Repositories\HistoryRepository;
use App\Repositories\SettingsRepository;

class TxtExporter
{
    protected $messages;
    protected $settingsRepository;
    protected $historyRepository;

    public function __construct()
    {
        $this->historyRepository = new HistoryRepository();
        $this->settingsRepository = new SettingsRepository();

        $lang = $this->settingsRepository->getSetting('localization', 'locale');

        $this->messages = $this->loadLocale($lang);
    }

    public function saveToTxt($pathToFile, $nameOfFile, $date = null)
    {
        $fullPathName = "{$pathToFile}{$nameOfFile}.txt";

        if (file_exists($fullPathName)) {
            $command = choice($this->getText('questions', 'text_file_exist', $fullPathName), [AGREE, DEGREE]);

            switch ($command) {
                case (AGREE):
                    file_put_contents($fullPathName, '');

                    break;
                case (DEGREE):
                    return PHP_EOL;
            }
        }

        $a = (is_null($date))
            ? $this->saveAllToTxt()
            : $this->saveByDateTxt($date);

        info($this->messages['info']['history_saved']);

        return file_put_contents($fullPathName, $a);
    }

    protected function getText($typeOfText, $text, $replacements)
    {
        return str_replace('%', $replacements, $this->messages[$typeOfText][$text]);
    }

    protected function loadLocale($lang)
    {
        $messages = file_get_contents(prepare_file_path("locale/{$lang}.json"));

        return json_decode($messages, true);
    }

    protected function writeHistoryLine($historyItem)
    {
        $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);
        $prefix = ($isBasicMathOperation) ? '   ' : '(!)';

        return "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";
    }

    protected function saveAllToTxt()
    {
        return $this->writeSaveText($this->historyRepository->allGroupedBy('date')) ;
    }

    protected function saveByDateTxt($date)
    {
        $data = $this->historyRepository->get(['date' => $date]);
        $dateByData = [$date => $data];

        return $this->writeSaveText($dateByData) ;
    }

    protected function writeSaveText($data)
    {
        $savedData = '';

        foreach ($data as $savedGroup => $saved) {
            $savedData .= "[{$savedGroup}]\n";

            foreach ($saved as $savedValue1) {
                $savedData .= $this->writeHistoryLine($savedValue1) . "\n";
            }
        }
        return $savedData;
    }
}
