<?php

namespace App\Exporters;

class HistoryConsoleExporter extends HistoryExporter
{
    protected $historyRepository;

    public function __construct()
    {
        return parent::__construct();
    }

    protected function output($date)
    {
        return $this->showHistoryItems($date);
    }
}