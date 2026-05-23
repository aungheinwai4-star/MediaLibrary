
<?php

use App\Controller\ApiCatalogController;
use App\Controller\ApiDetailsController;
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

define('BASE_PATH', dirname(__DIR__));//project directory name(path)(untill MVC)

// loading fles
require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/inc/Database.php';
require_once BASE_PATH . '/inc/CustomPath.php';

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

session_start();

/* USER SYSTEM */
$userRepo = new UserRepository($db);
$userService = new UserService($userRepo);
$userController = new UserController($userService);

/*ROUTING */
//if url has page use it
//otherwise use home
$page = $_GET['page'] ?? 'home';

//manual resourcebundle_get_error_code
//decide which controller/method should run

switch ($page) {

    case 'details':
        $controller = new DetailsController($catalogService);
        $controller->show();
        break;

    case 'suggest':
        $controller = new SuggestController($formatService);
        $controller->index();
        break;

    case 'catalog':
        $controller = new CatalogController($catalogService);
        $controller->index();
        break;

    //login and register routes
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

    default: // HOME PAGE
        $controller = new CatalogController($catalogService);
        $controller->home();
        break;
}

