<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Agregar Restaurante</title>
</head>
<body class="container mt-5">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FFCF01;">
        <div class="container">
            <a href="admin.php" class="btn btn-outline-secondary">Volver al CRUD</a>
            <a class="navbar-brand mx-auto d-flex" href="#">
                <img src="img/logo_cd.png" class="img-fluid" alt="Logo" style="max-height: 70px;">
            </a>
        </div>
    </nav>

    <h1 class="mt-4">Agregar Restaurante</h1>

    <form action="restaurante_guardar.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Restaurante</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Restaurante</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Restaurante</button>
    </form>
</body>
</html>
