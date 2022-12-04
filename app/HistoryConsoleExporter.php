<?php

namespace App\Exporters;

class HistoryConsoleExporter extends BaseHistoryExporter
{
    protected $historyRepository;

    public function __construct()
    {
        return parent::__construct();
    }

    protected function output($historyLine)
    {
        return info($historyLine, 1);
    }
}