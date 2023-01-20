<?php

namespace App\Services;

use App\Repositories\HistoryRepository;
use App\Exporters\historyConsoleExporter;
use App\Exporters\HistoryTxtExporter;

class HistoryService
{
    protected $historyRepository;
    protected $historyConsoleExporter;
    protected $historyTxtExporter;

    public function __construct() {
        $this->historyRepository = new HistoryRepository();
        $this->historyConsoleExporter = new HistoryConsoleExporter();
        $this->historyTxtExporter = new HistoryTxtExporter();
    }

    public function showHistory($output, $showDateHistory, $fullPathName)
    {
        do {
            $exporter = $this->historyConsoleExporter;

            if ($output === 'export') {
                $this->historyTxtExporter->setFilePath($fullPathName);

                $exporter = $this->historyTxtExporter;
            }

            $isDataValid = (is_date($showDateHistory) || in_array($showDateHistory, HISTORY_COMMANDS));

            if (!$isDataValid) {
                info ("Wrong Input");
                continue;
            }

            if ($showDateHistory === 'help') {
                show_info_block('Available commands', HISTORY_VIEWER_COMMANDS, 19, 71);
                $isDataValid = false;

                continue;
            }

            if ($showDateHistory === 'back') {
                return info('You are returned');
            }

            if ($showDateHistory === FULL) {
                $showDateHistory = null;
            } elseif (!$this->historyRepository->isExist(['date' => $showDateHistory])) {
                info('No history in that day');

                $isDataValid = false;
            }
        } while (!$isDataValid);

        if ($exporter instanceof HistoryTxtExporter) {
            info('History Saved in ' . $fullPathName);
        }

        return $exporter->export($showDateHistory);
    }
}