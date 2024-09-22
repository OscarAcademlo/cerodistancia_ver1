<?php
$host = 'localhost';
$user = 'root'; // tu usuario de MySQL
$password = ''; // tu contraseña de MySQL
$dbname = 'cerodistancia';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
