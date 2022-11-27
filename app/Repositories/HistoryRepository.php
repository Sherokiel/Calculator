<?php

namespace App\Repositories;

class HistoryRepository extends JsonBaseRepository
{
    public function __construct()
    {
        return parent::__construct('history');
    }

    public function createDataItem($dataContent)
    {
        $dataContent = array_slice($dataContent, 0, 5);

        $dataContentValid = array_key_exists('date', $dataContent) && array_key_exists('first_operand', $dataContent) && array_key_exists('second_operand', $dataContent) && array_key_exists('sign', $dataContent) && array_key_exists('result', $dataContent);

        if (!$dataContentValid) {
            die;
        }

        return parent::create($dataContent);
    }
}
