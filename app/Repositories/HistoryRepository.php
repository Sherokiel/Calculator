<?php

namespace App\Repositories;

use Exception;

class HistoryRepository extends JsonBaseRepository
{
    public function __construct()
    {
        return parent::__construct('history');
    }

    public function create($dataContent)
    {
        $defaultHistory = ["date", "first_operand", "second_operand", "sign", "result"];
        $dataContentValid = array_intersect_key($dataContent, array_flip($defaultHistory));

        if (count($dataContentValid) !== count($defaultHistory)) {
            throw new Exception('Всё сломалось');
        }

        return parent::create($dataContent);
    }
}
