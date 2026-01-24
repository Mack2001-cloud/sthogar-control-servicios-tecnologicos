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
use App\Controllers\AdminTecnicosController;
use App\Controllers\AdminAgentesVentasController;

require __DIR__ . '/../app/config/auth.php';
require __DIR__ . '/../app/helpers/view.php';
require __DIR__ . '/../app/helpers/flash.php';
require __DIR__ . '/../app/helpers/csrf.php';
require __DIR__ . '/../app/helpers/session.php';
require __DIR__ . '/../app/helpers/validators.php';
require __DIR__ . '/../app/helpers/auth.php';
require __DIR__ . '/../app/middlewares/AuthMiddleware.php';
require __DIR__ . '/../app/middlewares/RoleMiddleware.php';

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
start_secure_session($config);

$router = new Router();

$authController = new AuthController();
$dashboardController = new DashboardController();
$clientesController = new ClientesController();
$serviciosController = new ServiciosController();
$equiposController = new EquiposController();
$pagosController = new PagosController();
$adjuntosController = new AdjuntosController();
$exportController = new ExportController();
$adminTecnicosController = new AdminTecnicosController();
$adminAgentesVentasController = new AdminAgentesVentasController();

$adminMiddleware = ['auth_middleware', role_required_any(['admin', 'ventas'])];
$adminOnlyMiddleware = ['auth_middleware', role_required('admin')];
$generalMiddleware = ['auth_middleware', role_required_any(['admin', 'ventas', 'tecnico'])];

$router->add('GET', '/', function (): void {
    if (isset($_SESSION['user_id'])) {
        header('Location: ' . post_login_route());
        exit;
    }
    header('Location: ' . AUTH_LOGIN_ROUTE);
    exit;
});

$router->add('GET', AUTH_LOGIN_ROUTE, [$authController, 'loginForm']);
$router->add('POST', AUTH_LOGIN_POST_ROUTE, [$authController, 'loginPost']);
$router->add('GET', AUTH_LOGOUT_ROUTE, [$authController, 'logout']);

$router->add('GET', '/dashboard', [$dashboardController, 'index'], $adminMiddleware);

$router->add('GET', '/clientes', [$clientesController, 'index'], $generalMiddleware);
$router->add('GET', '/clientes/create', [$clientesController, 'createForm'], $generalMiddleware);
$router->add('POST', '/clientes/create', [$clientesController, 'create'], $generalMiddleware);
$router->add('GET', '/clientes/edit', [$clientesController, 'editForm'], $generalMiddleware);
$router->add('POST', '/clientes/edit', [$clientesController, 'update'], $generalMiddleware);
$router->add('POST', '/clientes/delete', [$clientesController, 'delete'], $generalMiddleware);

$router->add('GET', '/servicios', [$serviciosController, 'index'], $generalMiddleware);
$router->add('GET', '/instalaciones', [$serviciosController, 'instalaciones'], $generalMiddleware);
$router->add('GET', '/servicios/create', [$serviciosController, 'createForm'], $generalMiddleware);
$router->add('POST', '/servicios/create', [$serviciosController, 'create'], $generalMiddleware);
$router->add('GET', '/servicios/view', [$serviciosController, 'view'], $generalMiddleware);
$router->add('GET', '/servicios/edit', [$serviciosController, 'editForm'], $generalMiddleware);
$router->add('POST', '/servicios/edit', [$serviciosController, 'update'], $generalMiddleware);
$router->add('POST', '/servicios/status', [$serviciosController, 'updateStatus'], $generalMiddleware);
$router->add('POST', '/servicios/presupuesto', [$serviciosController, 'updateBudget'], $generalMiddleware);
$router->add('POST', '/servicios/documentacion', [$serviciosController, 'updateDocumentation'], $generalMiddleware);
$router->add('POST', '/servicios/delete', [$serviciosController, 'delete'], $adminMiddleware);

$router->add('GET', '/equipos', [$equiposController, 'index'], $generalMiddleware);
$router->add('GET', '/equipos/create', [$equiposController, 'createForm'], $adminMiddleware);
$router->add('POST', '/equipos/create', [$equiposController, 'create'], $adminMiddleware);
$router->add('GET', '/equipos/edit', [$equiposController, 'editForm'], $adminMiddleware);
$router->add('POST', '/equipos/edit', [$equiposController, 'update'], $adminMiddleware);
$router->add('POST', '/equipos/delete', [$equiposController, 'delete'], $adminMiddleware);

$router->add('POST', '/pagos/create', [$pagosController, 'create'], $generalMiddleware);
$router->add('POST', '/adjuntos/upload', [$adjuntosController, 'upload'], $generalMiddleware);

$router->add('GET', '/export/clientes.csv', [$exportController, 'clientes'], $adminMiddleware);
$router->add('GET', '/export/servicios.csv', [$exportController, 'servicios'], $adminMiddleware);

$router->add('GET', '/admin/tecnicos', [$adminTecnicosController, 'index'], $adminOnlyMiddleware);
$router->add('GET', '/admin/tecnicos/create', [$adminTecnicosController, 'createForm'], $adminOnlyMiddleware);
$router->add('POST', '/admin/tecnicos/create', [$adminTecnicosController, 'create'], $adminOnlyMiddleware);
$router->add('GET', '/admin/tecnicos/edit', [$adminTecnicosController, 'editForm'], $adminOnlyMiddleware);
$router->add('POST', '/admin/tecnicos/edit', [$adminTecnicosController, 'update'], $adminOnlyMiddleware);
$router->add('POST', '/admin/tecnicos/toggle', [$adminTecnicosController, 'toggle'], $adminOnlyMiddleware);
$router->add('POST', '/admin/tecnicos/delete', [$adminTecnicosController, 'delete'], $adminOnlyMiddleware);

$router->add('GET', '/admin/agentes-ventas', [$adminAgentesVentasController, 'index'], $adminOnlyMiddleware);
$router->add('GET', '/admin/agentes-ventas/create', [$adminAgentesVentasController, 'createForm'], $adminOnlyMiddleware);
$router->add('POST', '/admin/agentes-ventas/create', [$adminAgentesVentasController, 'create'], $adminOnlyMiddleware);
$router->add('GET', '/admin/agentes-ventas/edit', [$adminAgentesVentasController, 'editForm'], $adminOnlyMiddleware);
$router->add('POST', '/admin/agentes-ventas/edit', [$adminAgentesVentasController, 'update'], $adminOnlyMiddleware);
$router->add('POST', '/admin/agentes-ventas/toggle', [$adminAgentesVentasController, 'toggle'], $adminOnlyMiddleware);
$router->add('POST', '/admin/agentes-ventas/delete', [$adminAgentesVentasController, 'delete'], $adminOnlyMiddleware);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
