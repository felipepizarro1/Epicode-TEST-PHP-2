<?php
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
    <h1>Bienvenido, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Esta es la página principal después de iniciar sesión.</p>
    <p>Aquí puedes agregar la funcionalidad relacionada con la gestión de datos sensibles y otras características de tu aplicación.</p>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>