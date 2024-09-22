<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die('Producto no especificado');
}

$producto_id = $_GET['id'];

// Obtener los detalles del producto
$producto = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$producto->execute([$producto_id]);
$producto = $producto->fetch();

if (!$producto) {
    die('Producto no encontrado');
}

// Eliminar la imagen del producto si existe
if (!empty($producto['imagen']) && file_exists($producto['imagen'])) {
    unlink($producto['imagen']); // Elimina el archivo de imagen
}

// Eliminar el producto de la base de datos
$stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
$stmt->execute([$producto_id]);

// Redireccionar de nuevo a la lista de productos del restaurante
header("Location: productos_admin.php?restaurante=" . $producto['restaurante_id']);
exit();
?>
