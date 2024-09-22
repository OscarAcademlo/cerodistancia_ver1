<?php
include 'db.php';

// Verificar si el restaurante está especificado
if (!isset($_GET['restaurante_id'])) {
    die('Restaurante no especificado');
}

$restaurante_id = $_GET['restaurante_id'];

// Obtener el nombre del restaurante
$restaurante = $pdo->prepare("SELECT * FROM restaurantes WHERE id = ?");
$restaurante->execute([$restaurante_id]);
$restaurante = $restaurante->fetch();

if (!$restaurante) {
    die('Restaurante no encontrado');
}

// Obtener los productos de este restaurante
$productos = $pdo->prepare("SELECT * FROM productos WHERE restaurante_id = ?");
$productos->execute([$restaurante_id]);
$productos = $productos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Administrar Productos - <?= htmlspecialchars($restaurante['nombre'], ENT_QUOTES, 'UTF-8') ?></title>
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

    <h1 class="mt-4">Administrar Productos - <?= htmlspecialchars($restaurante['nombre'], ENT_QUOTES, 'UTF-8') ?></h1>
    <a href="productos_nuevo.php?restaurante_id=<?= $restaurante_id ?>" class="btn btn-primary mb-3">Agregar Producto</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= $producto['id'] ?></td>
                <td><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>$<?= number_format($producto['precio'], 2) ?></td>
                <td><img src="restaurantes/<?= $restaurante['carpeta'] ?>/img/<?= $producto['imagen'] ?>" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>" style="height: 60px;"></td>
                <td>
                    <a href="productos_editar.php?id=<?= $producto['id'] ?>&restaurante_id=<?= $restaurante_id ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="productos_eliminar.php?id=<?= $producto['id'] ?>&restaurante_id=<?= $restaurante_id ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
