<?php

namespace App\Exporters;

use App\Repositories\HistoryRepository;

abstract class BaseHistoryExporter
{
    protected $historyRepository;

    public function __construct()
    {
        $this->historyRepository = new HistoryRepository();
    }

    public function export($condition)
    {
        $data = $this->historyRepository->getGroupedBy('date', $condition);

        return $this->showHistoryItems($data);
    }

    protected function generateHistoryLine($historyItem)
    {
        $isBasicMathOperation = in_array($historyItem['sign'], BASIC_COMMANDS);
        $prefix = ($isBasicMathOperation) ? '   ' : '(!)';

        return "{$prefix} {$historyItem['first_operand']} {$historyItem['sign']} {$historyItem['second_operand']} = {$historyItem['result']}";
    }

    protected function showHistoryItems($data)
    {
        foreach ($data as $date => $historyItems) {
            $this->output("{$date}: ");

            foreach ($historyItems as $historyItem) {
                $historyLine = $this->generateHistoryLine($historyItem);

                $this->output($historyLine);
            }
        }
    }

    abstract protected function output($historyLine);
}
