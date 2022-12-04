<?php

namespace App\Exporters;

class HistoryConsoleExporter extends BaseHistoryExporter
{
    protected function output($historyLine)
    {
        return info($historyLine, 1);
    }
}