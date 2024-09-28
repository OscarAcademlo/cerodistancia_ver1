<?php

session_start();

// Verificar si el administrador ha iniciado sesión
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirigir al formulario de inicio de sesión
    header('Location: login.php');
    exit();
}
include 'db.php';

// Mostrar restaurantes
$restaurantes = $pdo->query("SELECT * FROM restaurantes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Administrar Restaurantes</title>
</head>
<body class="container mt-5">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FFCF01;">
        <div class="container">
            <a href="index.php" class="btn btn-outline-secondary">Volver al Home</a>
            <a class="navbar-brand mx-auto d-flex" href="#">
                <img src="img/logo_cd.png" class="img-fluid" alt="Logo" style="max-height: 70px;">
            </a>
        </div>
    </nav>
    
    <h1 class="mt-4">Administrar Restaurantes</h1>
    <a href="restaurante_nuevo.php" class="btn btn-primary mb-3">Agregar Restaurante</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($restaurantes as $restaurante): ?>
            <tr>
                <td><?= $restaurante['id'] ?></td>
                <td><?= $restaurante['nombre'] ?></td>
                <td><?= $restaurante['descripcion'] ?></td>
                <td>
                    <img src="restaurantes/<?= $restaurante['carpeta'] ?>/img/<?= $restaurante['imagen'] ?>" alt="<?= $restaurante['nombre'] ?>" style="height: 60px; width: auto;">
                </td>
                <td>
                    <a href="restaurante_editar.php?id=<?= $restaurante['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="restaurante_eliminar.php?id=<?= $restaurante['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    <a href="productos_admin.php?restaurante_id=<?= $restaurante['id'] ?>" class="btn btn-info btn-sm">Menús</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
