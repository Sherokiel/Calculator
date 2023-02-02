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
        return ['user_name', 'date', 'first_operand', 'second_operand', 'sign', 'result'];
    }
}
