<?php

namespace App\Contract;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $data): bool;

    public function findByEmail(string $email): ?array;
}
