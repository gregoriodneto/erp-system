<?php
require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

echo "Conectado com sucesso!";