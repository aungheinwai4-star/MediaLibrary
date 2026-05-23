<?php

namespace App\Contract;

interface BaseRepositoryInterface
{
    public function count(?string $category = null, ?string $search = null): int;

    public function getAll(?int $limit = null, int $offset = 0): array;

    public function findById(int $id): ?array;


    public function search(?string $search, ?string $category = null, ?int $limit = null, int $offset = 0): array;
}
