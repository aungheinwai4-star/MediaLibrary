<?php

namespace App\Repository;

use App\Contract\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected string $table = 'users';

    protected array $searchColumns = ['name', 'email'];

    protected string $primaryKey = 'id';

    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = ?',[$email]);
    }
}
