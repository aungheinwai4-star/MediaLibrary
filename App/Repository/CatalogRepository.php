<?php

namespace App\Repository;

use App\Contract\CatalogRepositoryInterface;
use PDO;

class CatalogRepository extends BaseRepository implements CatalogRepositoryInterface
{
    public function count(?string $category = null, ?string $search = null): int
    {
        return (int) $this->fetchValue(
            'CALL sp_search_catalog_count(:search, :category)',
            [
                ':search' => $search,
                ':category' => $category,
            ]
        );
    }

    public function getAll(?int $limit = null, int $offset = 0): array
    {
        return $this->fetchAll('CALL sp_get_full_catalog(?, ?)', [
            $limit,
            $offset,
        ]);
    }

    public function getByCategory(string $category, ?int $limit = null, int $offset = 0): array
    {
        return $this->fetchAll('CALL sp_get_catalog(?, ?, ?)', [
            $category,
            $limit,
            $offset,
        ]);
    }

    public function search(?string $search, ?string $category = null, ?int $limit = null, int $offset = 0): array
    {
        $stmt = $this->executeSP('sp_search_catalog(?, ?, ?, ?)', [
            $search,
            $category,
            $limit,
            $offset,
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->nextRowset();
        $stmt->closeCursor();

        return $data ?: [];
    }

    public function getRandom(): array
    {
        return $this->fetchAll('SELECT * FROM view_random');
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->executeSP('sp_get_item_full_detail(?)', [$id]);

        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            $stmt->closeCursor();
            return null;
        }

        $stmt->nextRowset();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $role = strtolower($row['role']);
            $item[$role][] = $row['fullname'];
        }

        $stmt->closeCursor();

        return $item;
    }
}
