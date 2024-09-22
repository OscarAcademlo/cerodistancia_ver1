<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $carpeta = strtolower(str_replace(' ', '_', $nombre));

    // Verificar si ya existe la carpeta
    if (!is_dir("restaurantes/$carpeta")) {
        mkdir("restaurantes/$carpeta/img", 0777, true);

        // Subir la imagen del restaurante
        $imagen = $_FILES['imagen']['name'];
        $target = "restaurantes/$carpeta/img/" . basename($imagen);

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target)) {
            // Guardar en la base de datos
            $stmt = $pdo->prepare("INSERT INTO restaurantes (nombre, descripcion, carpeta, imagen) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $carpeta, $imagen]);

            // Crear el archivo index.php para el restaurante
            $indexContent = "<?php
include '../../db.php';
\$productos = \$pdo->prepare('SELECT * FROM productos WHERE restaurante_id = ?');
\$productos->execute([{$pdo->lastInsertId()}]);
\$productos = \$productos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?php echo htmlspecialchars(\$nombre); ?></title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
    <div class='container mt-5'>
        <h1><?php echo htmlspecialchars(\$nombre); ?></h1>
        <p><?php echo htmlspecialchars(\$descripcion); ?></p>
        <h2>Productos</h2>
        <ul class='list-group'>
            <?php foreach (\$productos as \$producto): ?>
                <li class='list-group-item'>
                    <?php echo htmlspecialchars(\$producto['nombre']); ?> - $<?php echo number_format(\$producto['precio'], 2); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>";

            file_put_contents("restaurantes/$carpeta/index.php", $indexContent);

            // Redirigir de vuelta a la página de administración
            header('Location: admin.php');
            exit;
        } else {
            die('Error al subir la imagen');
        }
    } else {
        die('Ya existe un restaurante con este nombre');
    }
}
?>
