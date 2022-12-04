<?php

namespace App\Exporters;

class HistoryTxtExporter extends HistoryExporter
{
    public function __construct()
    {
        return parent::__construct();
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

    protected function output($date)
    {
        return $this->writeSaveText($date);
    }
}
