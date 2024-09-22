<?php
function generarIndexRestaurante($restaurante_id) {
    global $pdo;

    // Obtener la información del restaurante
    $stmt = $pdo->prepare("SELECT * FROM restaurantes WHERE id = ?");
    $stmt->execute([$restaurante_id]);
    $restaurante = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$restaurante) {
        die('Restaurante no encontrado');
    }

    // Obtener los productos del restaurante
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE restaurante_id = ?");
    $stmt->execute([$restaurante_id]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Crear el contenido del archivo index.php
    $index_content = "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <title>{$restaurante['nombre']}</title>
</head>
<body class='container mt-5'>
    <h1>{$restaurante['nombre']}</h1>
    <h2>Menú</h2>
    <div class='row'>";

    // Añadir cada producto al contenido
    foreach ($productos as $producto) {
        // Generar la ruta completa de la imagen
        $index_content .= "
        <div class='col-md-4'>
            <div class='card'>
                <img src='/delivery/{$producto['imagen']}' class='card-img-top' alt='{$producto['nombre']}' style='width: 100%; height: 200px; object-fit: cover;'>
                <div class='card-body'>
                    <h5 class='card-title'>{$producto['nombre']}</h5>
                    <p class='card-text'>{$producto['descripcion']}</p>
                    <p class='card-text'>\$" . number_format($producto['precio'], 2) . "</p>
                </div>
            </div>
        </div>";
    }

    $index_content .= "</div>
</body>
</html>";

    // Guardar el archivo index.php del restaurante
    $ruta_archivo = "restaurantes/{$restaurante['carpeta']}/index.php";
    file_put_contents($ruta_archivo, $index_content);
}
?>
