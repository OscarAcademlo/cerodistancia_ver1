<?php
include 'db.php';

// Verificar si el producto está especificado
if (!isset($_GET['id'])) {
    die('Producto no especificado');
}

$producto_id = $_GET['id'];

// Obtener la información del producto
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    die('Producto no encontrado');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Producto</title>
</head>
<body class="container mt-5">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FFCF01;">
        <div class="container">
            <a href="productos_admin.php?restaurante_id=<?= $producto['restaurante_id'] ?>" class="btn btn-outline-secondary">Volver a Productos</a>
            <a class="navbar-brand mx-auto d-flex" href="#">
                <img src="img/logo_cd.png" class="img-fluid" alt="Logo" style="max-height: 70px;">
            </a>
        </div>
    </nav>

    <h1 class="mt-4">Editar Producto</h1>

    <form action="productos_actualizar.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
        <input type="hidden" name="restaurante_id" value="<?= $producto['restaurante_id'] ?>">
        <input type="hidden" name="imagen_actual" value="<?= $producto['imagen'] ?>"> <!-- Campo oculto para la imagen actual -->
        
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" step="0.01" value="<?= $producto['precio'] ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Producto (dejar en blanco si no desea cambiarla)</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
            <p class="form-text">Imagen actual: <img src="<?= $producto['imagen'] ?>" alt="<?= $producto['nombre'] ?>" style="height: 50px;"></p>
        </div>
        
        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
    </form>
</body>
</html>
