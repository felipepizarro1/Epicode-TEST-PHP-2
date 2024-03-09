<?php

$config = require_once('config.php');
require_once('database.php');




// Verificamos si la base de datos está configurada
if (!$config['database_configured']) {
    // Intentar establecer la conexión sin seleccionar la base de datos
    $pdo = new PDO('mysql:host=' . $config['host'], $config['user'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Intentar crear la base de datos si no existe
    $stmt = $pdo->query("SHOW DATABASES LIKE '{$config['database']}'");
    if (!$stmt->fetch()) {
        $pdo->exec('CREATE DATABASE ' . $config['database']);
    }

    // Actualizar la configuración y guardarla en el archivo config.php
    $config['database_configured'] = true;
    file_put_contents('config.php', '<?php return ' . var_export($config, true) . '; ?>');
}

// Ahora que la base de datos está configurada, creamos la instancia de DB_PDO
$db = db\DB_PDO::getInstance($config);
$pdo = $db->getConnection();

session_start();

// Verificamos si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Si no ha iniciado sesión, redirigimos al usuario a la página de registro
    header('Location: register.php');
    exit();
}

// Aquí puedes incluir la lógica para la gestión de datos sensibles y otros elementos del panel de administración
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Encabezado, título y enlaces a estilos -->
</head>
<body>
    <!-- Barra de navegación, opciones de usuario, panel de administración, etc. -->
    <h1>Benvenuto <?php echo $_SESSION['username']; ?>!</h1>
    <p>Admin Panel</p>
    <p>Aquí puedes agregar la funcionalidad relacionada con la gestión de datos sensibles y otras características de tu aplicación.</p>

    <!--insertar PANEL -->
    <a href="logout.php">Logout</a>
</body>
</html>