<?php

namespace App\Repositories;

use Exception;

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
}