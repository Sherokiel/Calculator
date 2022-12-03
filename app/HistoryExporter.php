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

    public function export($date = null)
    {
        return (is_null($date))
            ? $this->exportAll()
            : $this->exportByDate($date);
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

    public function exportByDate($date)
    {
        $data = $this->historyRepository->get(['date' => $date]);

        return $this->showHistoryItems([$date => $data]);
    }

    public function exportAll()
    {
        $data = $this->historyRepository->allGroupedBy('date');

        return $this->showHistoryItems($data);
    }
}