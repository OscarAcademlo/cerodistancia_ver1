<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['id'];
    $restaurante_id = $_POST['restaurante_id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    // Verificar si se ha cargado una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_nombre = $_FILES['imagen']['name'];
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        
        // Obtener la carpeta del restaurante desde la base de datos
        $stmt = $pdo->prepare("SELECT carpeta FROM restaurantes WHERE id = ?");
        $stmt->execute([$restaurante_id]);
        $restaurante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$restaurante) {
            die('Restaurante no encontrado');
        }

        // Ruta destino para la imagen en la carpeta del restaurante correcto
        $ruta_destino = "restaurantes/{$restaurante['carpeta']}/img/" . basename($imagen_nombre);
        move_uploaded_file($imagen_tmp, $ruta_destino);

        // Actualizar el producto en la base de datos con la nueva imagen
        $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, imagen = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $precio, $ruta_destino, $producto_id]);
    } else {
        // Si no hay una nueva imagen, actualizar solo los demás campos
        $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $precio, $producto_id]);
    }

    // Volver a generar el archivo index.php del restaurante
    include 'generar_index_restaurante.php';
    generarIndexRestaurante($restaurante_id);

    header("Location: productos_admin.php?restaurante_id=$restaurante_id");
    exit;
}
?>
