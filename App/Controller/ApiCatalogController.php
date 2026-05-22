<?php

namespace App\Controller;

use App\Service\CatalogService;

class ApiCatalogController  
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function home(): void
    {
        header('Content-Type: application/json');

        $pageTitle = "Personal Media Library";
        $section = "catalog";

        $random = $this->catalogService->getHomePageData();

        echo json_encode([
            'success' => true,
            'pageTitle' => $pageTitle,
            'section' => $section,
            'random' => $random
        ]);
    }

    public function index(): void
    {
        header('Content-Type: application/json');

        $data = $this->catalogService->getCatalogPage($_GET);

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}