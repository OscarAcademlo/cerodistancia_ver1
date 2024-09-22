<?php
include 'db.php';

if (!isset($_GET['restaurante_id'])) {
    die('Restaurante no especificado');
}

$restaurante_id = $_GET['restaurante_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Agregar Producto</title>
</head>
<body class="container mt-5">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FFCF01;">
        <div class="container">
            <a href="productos_admin.php?restaurante_id=<?= $restaurante_id ?>" class="btn btn-outline-secondary">Volver a Menú</a>
            <a class="navbar-brand mx-auto d-flex" href="#">
                <img src="img/logo_cd.png" class="img-fluid" alt="Logo" style="max-height: 70px;">
            </a>
        </div>
    </nav>

    <h1 class="mt-4">Agregar Producto</h1>

    <form action="productos_guardar.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="restaurante_id" value="<?= $restaurante_id ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Producto</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Producto</button>
    </form>
</body>
</html>
