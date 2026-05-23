<?php

namespace App\Repository;

use App\Contract\FormatRepositoryInterface;
use PDO;

class FormatRepository extends BaseRepository implements FormatRepositoryInterface
{
    public function count(?string $category = null, ?string $search = null): int
    {
        return (int) $this->fetchValue(
            'CALL sp_count_formats(:search, :category)',
            [
                ':search' => $search,
                ':category' => $category,
            ]
        );
    }

    public function getAll(?int $limit = null, int $offset = 0): array
    {
        return $this->fetchAll(
            'CALL sp_get_all_formats(?, ?)',
            [$limit, $offset]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne(
            'CALL sp_get_format_by_id(?)',
            [$id]
        );
    }

    public function search(?string $search, ?string $category = null, ?int $limit = null, int $offset = 0): array
    {
        $stmt = $this->executeSP('sp_search_formats(?, ?, ?, ?)', [
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

    public function getFormatDropDown(?string $category = null): array
    {
        return $this->fetchAll(
            'CALL sp_get_format_dropdown(?)',
            [$category]
        );
    }

    public function getCategoryDropDown(): array
    {
        return $this->fetchAll('CALL sp_get_category_dropdown()');
    }

    public function getGenresDropDown(?string $category = null): array
    {
        return $this->fetchAll(
            'CALL sp_get_genres_dropdown(?)',
            [$category]
        );
    }
}
