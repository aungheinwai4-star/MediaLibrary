<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

use App\Inc\Auth;

use App\Controller\ApiCatalogController;
use App\Controller\ApiDetailsController;
use App\Controller\ApiUserController;
use App\Controller\CatalogController;
use App\Controller\DetailsController;
use App\Controller\SuggestController;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Controller\UserController;

use App\Repository\CatalogRepository;
use App\Repository\FormatRepository;

use App\Service\CatalogService;
use App\Service\FormatService;

define('BASE_PATH', dirname(__DIR__)); //project directory name(path)(untill MVC)

// loading fles
require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/App/Inc/Database.php';
require_once BASE_PATH . '/App/Inc/CustomPath.php';

//loading environment variables for .env
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/*BUILD SHARED OBJECTS*/

$db = Database::getConnection();

/* Repositories */
$catalogRepo = new CatalogRepository($db);
$formatRepo  = new FormatRepository($db);

/* Services */
$catalogService = new CatalogService($catalogRepo);
$formatService  = new FormatService($formatRepo);

$catalogController = new CatalogController($catalogService);
$detailsController = new detailsController($catalogService);

//Api
$apiCatalogController = new ApiCatalogController($catalogService);
$apiDetailsController = new ApiDetailsController($catalogService);

/* USER SYSTEM */
$userRepo = new UserRepository($db);
$userService = new UserService($userRepo);
$userController = new UserController($userService);
$apiUserController = new ApiUserController($userService);

/*ROUTING */
//if url has page use it
//otherwise use home
$page = $_GET['page'] ?? 'home';

$protectedPages = [
    'home',
    'catalog',
    'details',
    'suggest'
];

if (in_array($page, $protectedPages)) {
    Auth::requireLogin();
}
//manual resourcebundle_get_error_code
//decide which controller/method should run

switch ($page) {

    case 'details':
        $detailsController->show();
        break;

    case 'suggest':
        $controller = new SuggestController($formatService);
        $controller->index();
        break;

    case 'catalog':
        $catalogController->index();
        break;

    /* 
    Api for catalog, details
    */
    case 'api-catalog':
        $apiCatalogController->index();
        break;

    case 'api-details':
        $apiDetailsController->show();
        break;

    /* 
    login and register route
    */
    case 'login':
        $userController->showLogin();
        break;

    case 'register':
        $userController->showRegister();
        break;

    case 'login-submit':
        $userController->login();
        break;

    case 'register-submit':
        $userController->register();
        break;

    case 'logout':
        $userController->logout();
        break;

    //API Auth Routes
    case 'api-login':
        $apiUserController->login();
        break;

    case 'api-register':
        $apiUserController->register();
        break;

    case 'api-logout':
        $apiUserController->logout();
        break;

    default:
        $userController->showLogin();
        break;
}

