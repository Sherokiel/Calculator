<?php

namespace App\Exporters;

class HistoryConsoleExporter extends HistoryExporter
{
    protected $historyRepository;

    public function __construct()
    {
        return parent::__construct();
    }

    public function saveByDate($date)
    {
        $data = $this->historyRepository->get(['date' => $date]);

        return $this->showHistoryItems([$date => $data]);
    }

    public function saveAll()
    {
        return $this->showHistoryItems($this->historyRepository->allGroupedBy('date'));
    }
}