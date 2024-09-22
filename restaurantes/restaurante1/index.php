<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../db.php'; // Ruta para conectarse a la base de datos desde la carpeta del restaurante

// Obtener el ID del restaurante desde el nombre de la carpeta actual
$restauranteNombre = basename(__DIR__);
$restaurante = $pdo->prepare("SELECT * FROM restaurantes WHERE carpeta = ?");
$restaurante->execute([$restauranteNombre]);
$restauranteData = $restaurante->fetch(PDO::FETCH_ASSOC);

// Verificar si el restaurante existe
if (!$restauranteData) {
    echo "Restaurante no encontrado.";
    exit;
}

// Obtener los productos de este restaurante
$productos = $pdo->prepare("SELECT * FROM productos WHERE restaurante_id = ?");
$productos->execute([$restauranteData['id']]);
$productos = $productos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title><?= $restauranteData['nombre'] ?> - Cero Distancia</title>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="background-color: #FFCF01;">
    <div class="container">
        <a href="../../index.php" class="btn btn-outline-secondary">Volver al Home</a>
        <a class="navbar-brand mx-auto" href="#">
            <img src="../../img/logo_cd.png" class="img-fluid" alt="Logo" style="max-height: 70px;">
        </a>
    </div>
</nav>

<div class="container mt-4">
    <h4>Menú de <?= $restauranteData['nombre'] ?></h4>
    <div class="row">
        <?php foreach ($productos as $producto): ?>
        <div class="col-md-4 d-flex align-items-stretch">
            <div class="card mb-4 w-100" style="max-height: 450px;">
                <img src="<?= "./img/" . basename($producto['imagen']) ?>" class="card-img-top" alt="<?= $producto['nombre'] ?>" style="height: 250px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                    <p class="card-text"><?= $producto['descripcion'] ?></p>
                    <p class="fw-bold mt-auto">$<?= $producto['precio'] ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
