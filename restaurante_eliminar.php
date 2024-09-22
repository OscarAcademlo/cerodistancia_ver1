<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener la carpeta del restaurante
    $stmt = $pdo->prepare("SELECT carpeta FROM restaurantes WHERE id = ?");
    $stmt->execute([$id]);
    $restaurante = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($restaurante) {
        $carpeta = $restaurante['carpeta'];

        // Eliminar carpeta y contenido del restaurante
        function eliminarDirectorio($carpeta) {
            foreach(glob($carpeta . '/*') as $archivo) {
                if (is_dir($archivo))
                    eliminarDirectorio($archivo);
                else
                    unlink($archivo);
            }
            rmdir($carpeta);
        }

        eliminarDirectorio("restaurantes/$carpeta");

        // Eliminar de la base de datos
        $stmt = $pdo->prepare("DELETE FROM restaurantes WHERE id = ?");
        $stmt->execute([$id]);
    }

    header("Location: admin.php");
    exit();
}
?>
