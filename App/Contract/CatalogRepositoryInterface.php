<?php

namespace App\Contract;
interface CatalogRepositoryInterface
{
    // Get total catalog item count
    public function getcatalog_count($category = null, $search = null);

    // Get complete catalog list
    public function get_full_catalog($limit = null, $offset = 0);

    // Get catalog items by category
    public function get_category_catalog($category, $limit = null, $offset = 0);

    // Search catalog items by keyword and category
    public function get_search_catalog($search, $category = null, $limit = null, $offset = 0);

    // Get random catalog items
    public function get_random_catalog();

    // Get a single catalog item by ID
    public function get_single_item($id);
}