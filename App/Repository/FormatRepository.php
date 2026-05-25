<?php

namespace App\Repository;

use App\Contract\FormatRepositoryInterface;

class FormatRepository extends BaseRepository implements FormatRepositoryInterface
{
    /* =========================
     * PROCEDURE CONFIGURATION
     * ========================= */

    protected string $source = 'procedure';

    protected ?string $countProcedure = 'sp_count_formats(:search, :category)';

    protected ?string $listProcedure = 'sp_get_all_formats(?, ?)';

    protected ?string $searchProcedure = 'sp_search_formats';

    protected ?string $findProcedure = 'sp_get_format_by_id(?)';

    /* =========================
     * UNIQUE BUSINESS METHODS
     * ========================= */

    public function getFormatDropDown(?string $category = null): array
    {
        return $this->fetchAll(
            'CALL sp_get_formats_by_category(?)',
            [$category]
        );
    }

    public function getCategoryDropDown(?string $category = null): array
    {
        return $this->fetchAll(
            'CALL sp_get_formats_by_category(?)',
            [$category]
        );
    }

    public function getGenresDropDown(?string $category = null): array
    {
        return $this->fetchAll(
            'CALL sp_get_genres_by_category(?)',
            [$category]
        );
    }
}
