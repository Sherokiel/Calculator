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

    public function export($output, $date, $fullPathName)
    {

        $condition = (is_null($date))
            ? []
            : ['date' => $date];

        $condition = $condition + ['user_name' => $this->user];

        if ($output === 'export') {
            $this->historyTxtExporter->setFilePath($fullPathName)->export($condition);
        }



        $this->historyConsoleExporter->export($condition);
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}