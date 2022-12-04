<?php

namespace App\Exporters;

use App\Repositories\HistoryRepository;

class DataExporter
{
    protected $historyRepository;

    public function __construct()
    {
        $this->historyRepository = new HistoryRepository();
    }

    protected function writeHistoryLine($historyItem)
    {
        $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);
        $prefix = ($isBasicMathOperation) ? '   ' : '(!)';

        return "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";
    }
}
