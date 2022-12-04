<?php

namespace App\Exporters;

use App\Repositories\HistoryRepository;

abstract  class  HistoryExporter
{
    protected $historyRepository;

    public function __construct()
    {
        $this->historyRepository = new HistoryRepository();
    }

    public function export($date = null)
    {
        return (is_null($date))
            ? $this->saveAll()
            : $this->saveByDate($date);
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

    public function saveAll()
    {
        return $this->output($this->historyRepository->allGroupedBy('date'));
    }

    public function saveByDate($date)
    {
        $data = $this->historyRepository->get(['date' => $date]);

        return $this->output([$date => $data]);
    }

    abstract protected function output($date);
}
