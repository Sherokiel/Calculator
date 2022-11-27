<?php

namespace App\Repositories;

class HistoryRepository extends JsonBaseRepository
{
    public function __construct()
    {
        return parent::__construct('history');
    }

    public function create($dataContent)
    {
        $dataContentValid = array_intersect_key($dataContent, array_flip(["date", "first_operand", "second_operand", "sign", "result"]));

        if (count($dataContentValid) <= 5) {
            die;
        }

        return parent::create($dataContent);
    }
}
