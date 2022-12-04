<?php

namespace App\Exporters;

class HistoryConsoleExporter extends HistoryExporter
{
    protected $historyRepository;

    public function __construct()
    {
        return parent::__construct();
    }

    public function export($date = null)
    {
        return (is_null($date))
            ? $this->exportAll()
            : $this->exportByDate($date);
    }

    public function exportByDate($date)
    {
        $data = $this->historyRepository->get(['date' => $date]);

        return $this->showHistoryItems([$date => $data]);
    }

    public function exportAll()
    {
        return $this->showHistoryItems($this->historyRepository->allGroupedBy('date'));
    }
}