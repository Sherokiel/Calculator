<?php

namespace App\Exporters;

class HistoryTxtExporter extends BaseHistoryExporter
{
    protected $savedData;
    protected $fullPathName;

    public function __construct()
    {
        return parent::__construct();
    }

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
