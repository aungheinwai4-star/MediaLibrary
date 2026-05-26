<?php 

namespace App\Repository;

use App\Contract\CatalogRepositoryInterface;
use PDO;

class CatalogRepository extends BaseRepository implements CatalogRepositoryInterface
{
    /* =========================
     * PROCEDURE CONFIGURATION
     * ========================= */

    protected string $source = 'procedure';

    protected ?string $countProcedure = 'sp_search_catalog_count';
    
    protected ?string $listProcedure = 'sp_get_full_catalog';

    protected ?string $searchProcedure = 'sp_search_catalog';

    protected ?string $findProcedure = 'sp_get_item_full_detail';

    /* =========================
     * CUSTOM METHODS ONLY
     * ========================= */

    public function getByCategory(string $category, ?int $limit = null, int $offset = 0): array
    {
        return $this->fetchAll(
            'CALL sp_get_catalog(?, ?, ?)',
            [$category, $limit, $offset]
        );
    }

    public function getRandom(): array
    {
        return $this->fetchAll('SELECT * FROM view_random');
    }
}
