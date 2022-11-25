<?php

namespace App\Repositories;

class HistoryRepository extends JsonBaseRepository
{
    public function __construct()
    {
        return parent::__construct('history.json');
    }

    public function create($date, $argument1, $argument2, $command, $result)
    {

        return parent::create([
            'date' => $date,
            'first_operand' => $argument1,
            'second_operand' => $argument2,
            'sign' => $command,
            'result' => $result
        ]);
    }
}
