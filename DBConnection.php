<?php

class DBConnection {
    private static $instance = null;
    private $pdo;

    private function __construct(array $config) {
        $dsn = "mysql:host={$config['host']};port={$config['port']};charset=utf8mb4";

        try {
            // Intentar conectarse sin seleccionar la base de datos
            $this->pdo = new PDO($dsn, $config['user'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Verificar si la base de datos existe, y si no, crearla
            $dbName = $config['database'];
            $stmt = $this->pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbName'");
            if (!$stmt->fetch()) {
                $this->pdo->exec("CREATE DATABASE $dbName");
            }

            // Finalmente, conectar a la base de datos seleccionada
            $this->pdo->exec("USE $dbName");

            // Verificar si la tabla de usuarios existe, y si no, crearla
            $stmt = $this->pdo->query("SHOW TABLES LIKE 'usuarios'");
            if (!$stmt->fetch()) {
                $this->pdo->exec("CREATE TABLE usuarios (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) NOT NULL,
                    password VARCHAR(255) NOT NULL
                )");
            }
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance(array $config) {
        if (!self::$instance) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}