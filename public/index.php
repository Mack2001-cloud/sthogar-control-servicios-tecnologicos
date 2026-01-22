<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ClientesController;
use App\Controllers\ServiciosController;
use App\Controllers\EquiposController;
use App\Controllers\PagosController;
use App\Controllers\AdjuntosController;
use App\Controllers\ExportController;

require __DIR__ . '/../app/helpers/view.php';
require __DIR__ . '/../app/helpers/flash.php';
require __DIR__ . '/../app/helpers/csrf.php';
require __DIR__ . '/../app/helpers/validators.php';
require __DIR__ . '/../app/helpers/auth.php';
require __DIR__ . '/../app/middlewares/auth.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$config = require __DIR__ . '/../app/config/config.php';

session_name($config['session_name']);
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Strict',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
]);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$router = new Router();

$authController = new AuthController();
$dashboardController = new DashboardController();
$clientesController = new ClientesController();
$serviciosController = new ServiciosController();
$equiposController = new EquiposController();
$pagosController = new PagosController();
$adjuntosController = new AdjuntosController();
$exportController = new ExportController();

$router->add('GET', '/', function (): void {
    if (isset($_SESSION['user_id'])) {
        header('Location: /dashboard');
        exit;
    }
    header('Location: /auth/login');
    exit;
});

$router->add('GET', '/auth/login', [$authController, 'loginForm']);
$router->add('POST', '/auth/login', [$authController, 'login']);
$router->add('GET', '/auth/logout', [$authController, 'logout']);

$router->add('GET', '/dashboard', [$dashboardController, 'index'], ['auth_middleware']);

$router->add('GET', '/clientes', [$clientesController, 'index'], ['auth_middleware']);
$router->add('GET', '/clientes/create', [$clientesController, 'createForm'], ['auth_middleware']);
$router->add('POST', '/clientes/create', [$clientesController, 'create'], ['auth_middleware']);
$router->add('GET', '/clientes/edit', [$clientesController, 'editForm'], ['auth_middleware']);
$router->add('POST', '/clientes/edit', [$clientesController, 'update'], ['auth_middleware']);
$router->add('POST', '/clientes/delete', [$clientesController, 'delete'], ['auth_middleware']);

$router->add('GET', '/servicios', [$serviciosController, 'index'], ['auth_middleware']);
$router->add('GET', '/servicios/create', [$serviciosController, 'createForm'], ['auth_middleware']);
$router->add('POST', '/servicios/create', [$serviciosController, 'create'], ['auth_middleware']);
$router->add('GET', '/servicios/view', [$serviciosController, 'view'], ['auth_middleware']);
$router->add('GET', '/servicios/edit', [$serviciosController, 'editForm'], ['auth_middleware']);
$router->add('POST', '/servicios/edit', [$serviciosController, 'update'], ['auth_middleware']);
$router->add('POST', '/servicios/status', [$serviciosController, 'updateStatus'], ['auth_middleware']);
$router->add('POST', '/servicios/delete', [$serviciosController, 'delete'], ['auth_middleware']);

$router->add('GET', '/equipos', [$equiposController, 'index'], ['auth_middleware']);
$router->add('GET', '/equipos/create', [$equiposController, 'createForm'], ['auth_middleware']);
$router->add('POST', '/equipos/create', [$equiposController, 'create'], ['auth_middleware']);
$router->add('GET', '/equipos/edit', [$equiposController, 'editForm'], ['auth_middleware']);
$router->add('POST', '/equipos/edit', [$equiposController, 'update'], ['auth_middleware']);
$router->add('POST', '/equipos/delete', [$equiposController, 'delete'], ['auth_middleware']);

$router->add('POST', '/pagos/create', [$pagosController, 'create'], ['auth_middleware']);
$router->add('POST', '/adjuntos/upload', [$adjuntosController, 'upload'], ['auth_middleware']);

$router->add('GET', '/export/clientes.csv', [$exportController, 'clientes'], ['auth_middleware']);
$router->add('GET', '/export/servicios.csv', [$exportController, 'servicios'], ['auth_middleware']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
