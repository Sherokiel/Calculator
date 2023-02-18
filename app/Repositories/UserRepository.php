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
        return ['user_name', 'password'];
    }
}