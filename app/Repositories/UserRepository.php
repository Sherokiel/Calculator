<?php

namespace App\Repositories;

class UserRepository extends JsonBaseRepository
{
    const ROLE_BASIC = 'basic';

    public function __construct()
    {
        return parent::__construct('users');
    }

    protected function getEntityFields(): array
    {
        return ['user_name', 'password'];
    }
}