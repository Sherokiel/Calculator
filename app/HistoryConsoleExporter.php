<?php

namespace App\Exporters;

class HistoryConsoleExporter extends BaseHistoryExporter
{
    protected function showHistoryItems($data)
    {
        parent::showHistoryItems($data);

        return write_symbol_line(15, '=');
    }

    protected function output($historyLine)
    {
        return info($historyLine, 1);
    }
}