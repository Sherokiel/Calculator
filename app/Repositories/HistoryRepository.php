<?php

namespace App\Repositories;

class HistoryRepository extends JsonBaseRepository
{
    public function __construct()
    {
        return parent::__construct('history');
    }

    protected function getEntityFields(): array
    {
        return ['user', 'date', 'first_operand', 'second_operand', 'sign', 'result'];
    }

}
