<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function all(): ?array;

    public function create(array $item): array;

    public function allGroupedBy(string $field): array;

    public function isExist(array $condition = []): bool;

    public function first(array $condition = []): ?array;

    public function get(array $condition): array;

    public function getGroupedBy(string $field, array $condition = []): array;
}
