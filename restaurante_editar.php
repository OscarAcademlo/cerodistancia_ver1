<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $restaurante = $pdo->prepare("SELECT * FROM restaurantes WHERE id = ?");
    $restaurante->execute([$id]);
    $restauranteData = $restaurante->fetch(PDO::FETCH_ASSOC);

    if (!$restauranteData) {
        echo "Restaurante no encontrado.";
        exit;
    }
}

// Actualizar restaurante
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    // Verificar si se cargó una nueva imagen
    if (isset($_FILES['imagen']['tmp_name']) && $_FILES['imagen']['tmp_name'] != '') {
        $imagenNombre = $_FILES['imagen']['name'];
        $carpeta = $restauranteData['carpeta'];
        $imagenRuta = "restaurantes/$carpeta/img/" . basename($imagenNombre);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagenRuta);
        
        // Actualizar con nueva imagen
        $stmt = $pdo->prepare("UPDATE restaurantes SET nombre = ?, descripcion = ?, imagen = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $imagenNombre, $id]);
    } else {
        // Actualizar sin cambiar imagen
        $stmt = $pdo->prepare("UPDATE restaurantes SET nombre = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $id]);
    }

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Restaurante</title>
</head>
<body class="container mt-5">
    <h1>Editar Restaurante</h1>
    <form action="restaurante_editar.php?id=<?= $restauranteData['id'] ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $restauranteData['id'] ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Restaurante</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?= $restauranteData['nombre'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required><?= $restauranteData['descripcion'] ?></textarea>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Restaurante</label>
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
            <p>Imagen actual: <img src="restaurantes/<?= $restauranteData['carpeta'] ?>/img/<?= $restauranteData['imagen'] ?>" style="height: 60px;"></p>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="admin.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>
