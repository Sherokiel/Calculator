<?php

namespace App\Repositories;

class HistoryRepository extends JsonBaseRepository
{
    public function __construct()
    {
        return parent::__construct('history');
    }

    protected function getEntityFields()
    {
        return $defaultJson = ["date", "first_operand", "second_operand", "sign", "result"];
    }

    public function create($item)
    {
        return parent::create($item);
    }
}
