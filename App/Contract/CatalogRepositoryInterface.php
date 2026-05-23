<?php

namespace App\Contract;

interface CatalogRepositoryInterface extends BaseRepositoryInterface
{
    public function getRandom(): array;

    public function getByCategory(string $category, ?int $limit = null, int $offset = 0): array;
}
