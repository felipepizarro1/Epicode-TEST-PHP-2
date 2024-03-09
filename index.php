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

//CRUD
$users = $db->getAllUsers();

if (isset($_POST['delete_user_id'])) {
    $user_id = $_POST['delete_user_id'];
    $db->deleteUser($user_id);
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<body>
    <div class="container mt-5">
        <h1>Benvenut@ <?php echo $_SESSION['username']; ?>!</h1>
        <p>Admin Panel</p>
        <p>You can manage all users data here:</p>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usernames</th>
                    <th>Passwords</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['password']; ?></td>
                    <td class="d-flex gap-2">
                        <!-- <form action="index.php" method="post">
                            <input type="hidden" name="edit_user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form> -->
                        <form action="index.php" method="post">
                            <input type="hidden" name="delete_user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="logout.php" class="btn btn-primary">Logout</a>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>