<?php

namespace App\Exporters;

use App\Repositories\HistoryRepository;

class HistoryExporter
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

    protected function showHistoryItems($data)
    {
        foreach ($data as $date => $historyItems) {
            info("{$date}: ");

            foreach ($historyItems as $historyItem) {
                $historyFunction = $this->writeHistoryLine($historyItem);

                info($historyFunction, 1);
            }
        }

        return write_symbol_line(15, '=');
    }
}
