<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurante_id = $_POST['restaurante_id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    // Verificar si la imagen se ha subido correctamente
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

        // Guardar el producto en la base de datos
        $stmt = $pdo->prepare("INSERT INTO productos (restaurante_id, nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$restaurante_id, $nombre, $descripcion, $precio, $ruta_destino]);

        // Actualizar la vista del restaurante
        include 'generar_index_restaurante.php';
        generarIndexRestaurante($restaurante_id);

        header("Location: productos_admin.php?restaurante_id=$restaurante_id");
        exit;
    } else {
        echo "Error al cargar la imagen.";
    }
}
?>
