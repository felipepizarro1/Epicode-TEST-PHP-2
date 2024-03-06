<?php

return [
    'host' => 'localhost',
    'database' => 'testphp2',
    'user' => 'root',
    'password' => '',
    'port' => '3306' // Si es diferente, cambia este valor
];
// Verificar y crear la base de datos si no existe
$pdo = new PDO("mysql:host={$config['host']};port={$config['port']}", $config['user'], $config['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$databaseExists = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$config['database']}'")->fetch(PDO::FETCH_ASSOC);
if (!$databaseExists) {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['database']}");
}

// Establecer la conexión a la base de datos completa
$pdo = new PDO("mysql:host={$config['host']};dbname={$config['database']};port={$config['port']};charset=utf8mb4", $config['user'], $config['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

return $pdo;
?>

