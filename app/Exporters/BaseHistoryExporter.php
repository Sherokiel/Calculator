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

    public function export($date)
    {
//        return (is_null($date))
//            ? $this->exportAll()
//            : $this->exportBy($date);

        return $this->exportBy($date);

    }

    protected function exportBy($date)
    {
//        $data = $this->historyRepository->get(['date' => $date]);

        $data = $this->historyRepository->get($date);
        $date = implode($date);

        if($date === []) {
            return $this->showHistoryItems($this->historyRepository->allGroupedBy('date'));
        }

        return $this->showHistoryItems([$date => $data]);
    }

//    protected function exportAll()
//    {
//        return $this->showHistoryItems($this->historyRepository->allGroupedBy('date'));
//    }

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
