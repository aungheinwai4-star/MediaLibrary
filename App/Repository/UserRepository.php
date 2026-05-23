<?php

namespace App\Repository;

use App\Contract\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function count(?string $category = null, ?string $search = null): int
    {
        if ($search !== null) {
            return (int) $this->fetchValue(
                'SELECT COUNT(*) FROM users WHERE name LIKE ? OR email LIKE ?',
                ["%{$search}%", "%{$search}%"]
            );
        }

        return (int) $this->fetchValue('SELECT COUNT(*) FROM users');
    }

    public function getAll(?int $limit = null, int $offset = 0): array
    {
        if ($limit !== null) {
            return $this->fetchAll(
                'SELECT id, name, email FROM users LIMIT ? OFFSET ?',
                [$limit, $offset]
            );
        }

        return $this->fetchAll('SELECT id, name, email FROM users');
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT id, name, email FROM users WHERE id = ?',
            [$id]
        );
    }

    public function search(?string $search, ?string $category = null, ?int $limit = null, int $offset = 0): array
    {
        $sql = 'SELECT id, name, email FROM users WHERE name LIKE ? OR email LIKE ?';
        $params = ["%{$search}%", "%{$search}%"];

        if ($limit !== null) {
            $sql .= ' LIMIT ? OFFSET ?';
            $params[] = $limit;
            $params[] = $offset;
        }

        return $this->fetchAll($sql, $params);
    }

    public function create(array $data): bool
    {
        return $this->execute(
            'INSERT INTO users (name, email, password) VALUES (?, ?, ?)',
            [
                $data['name'],
                $data['email'],
                $data['password'],
            ]
        );
    }

    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne(
            'SELECT * FROM users WHERE email = ?',
            [$email]
        );
    }
}
