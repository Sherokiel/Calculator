<?php

namespace App\Exporters;

class HistoryTxtExporter extends BaseHistoryExporter
{
    protected $savedData;
    protected $fullPathName;

    protected function output($historyLine)
    {
        $this->savedData .= $historyLine . PHP_EOL;
    }

    public function export($date = null)
    {
        parent::export($date);

        return file_put_contents($this->fullPathName, $this->savedData);
    }

    public function setFilePath($filePath)
    {
        $this->fullPathName = $filePath;
    }
}
