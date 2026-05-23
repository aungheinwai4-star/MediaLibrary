<?php

namespace App\Contract;

interface FormatRepositoryInterface extends BaseRepositoryInterface
{
    public function getFormatDropDown(?string $category = null): array;

    public function getCategoryDropDown(): array;

    public function getGenresDropDown(?string $category = null): array;
}
