<?php

namespace App\Exporters;

class HistoryTxtExporter extends HistoryExporter
{
    public function __construct()
    {
        return parent::__construct();
    }

    public function exportToTxt($fullPathName, $date = null)
    {
        $content = (is_null($date))
            ? $this->saveAllToTxt()
            : $this->saveByDateTxt($date);

        return file_put_contents($fullPathName, $content);
    }

    protected function saveAllToTxt()
    {
        return $this->writeSaveText($this->historyRepository->allGroupedBy('date')) ;
    }

    protected function saveByDateTxt($date)
    {
        $data = $this->historyRepository->get(['date' => $date]);

        return $this->writeSaveText([$date => $data]);
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
