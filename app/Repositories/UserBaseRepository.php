<?php

namespace App\Repositories;

class UserRepository extends JsonBaseRepository
{
    public function __construct()
    {
        return parent::__construct('users');
    }

    protected function getEntityFields(): array
    {
        return ['username', 'password'];
    }

    public function getFirst($condition)
    {
        foreach ($this->all() as $value) {
            if ($this->isSuitableRecord($condition, $value)) {
                return $value;
            }
        }
        return null;
    }
}