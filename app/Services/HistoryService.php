<?php

namespace App\Services;

use App\Repositories\HistoryRepository;
use App\Exporters\HistoryConsoleExporter;
use App\Exporters\HistoryTxtExporter;

class HistoryService
{
    protected $historyRepository;
    protected $historyConsoleExporter;
    protected $historyTxtExporter;

    protected $user;

    public function __construct()
    {
        $this->historyRepository = new HistoryRepository();
        $this->historyConsoleExporter = new HistoryConsoleExporter();
        $this->historyTxtExporter = new HistoryTxtExporter();
    }

    public function export($output, $showDateHistory, $fullPathName)
    {
        $exporter = $this->historyConsoleExporter;

        if ($output === 'export') {
            return $this->historyTxtExporter->setFilePath($fullPathName)->export($showDateHistory);
        }

        return $exporter->export($showDateHistory);
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}