<?php
session_start();

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Contraseña definida para acceder al área de administración
    $admin_password = 'Gaylord2024';

    // Verificar si la contraseña ingresada es correcta
    if ($_POST['password'] === $admin_password) {
        // Establecer una variable de sesión para indicar que el administrador ha iniciado sesión
        $_SESSION['admin_logged_in'] = true;
        // Redirigir a la página de administración
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Contraseña incorrecta. Inténtalo de nuevo.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Inicio de Sesión Administrador</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Ingresar</button>
    </form>
</body>
</html>
