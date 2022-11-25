<?php

namespace App\Repositories;

class HistoryRepository extends JsonBaseRepository
{
    public function __construct()
    {
        return parent::__construct('history.json');
    }
}
