<?php
require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;
use App\Core\Router;

$db = new Database();
$conn = $db->getConnection();

$router = new Router();

$routes = require_once __DIR__ . '/../routes/web.php';
$routes($router, $conn);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($method, $uri);

error_log($_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI']);

echo "Conectado com sucesso!";