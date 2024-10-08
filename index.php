<?php
include 'db.php';

// Obtener la lista de restaurantes
$restaurantes = $pdo->query("SELECT * FROM restaurantes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cero Distancia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .restaurant-card {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .restaurant-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .restaurant-info {
            padding: 15px;
        }
        .restaurant-info h5 {
            margin: 0;
            font-size: 1.2rem;
        }
        .restaurant-info p {
            margin: 0;
            color: #6c757d;
        }
        .search-bar {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .header {
            background-color: #FFCF01;
            padding: 15px 20px;
        }
        .footer {
            background-color: #FFCF01;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #ddd;
        }
        .navbar-brand img {
            max-height: 70px;
            height: auto;
        }
        .navbar-nav .nav-link {
            color: white !important;
            font-size: 24px;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #FFCF01;
            border-radius: 50%;
        }
        a {
            color: inherit;
            text-decoration: none;
        }
    </style>
</head>
<body>

<!-- Encabezado -->
<nav class="navbar navbar-expand-lg header">
    <div class="container d-flex justify-content-between align-items-center">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand mx-auto d-flex" href="#">
            <img src="img/logo_cd.png" class="img-fluid" alt="Logo">
        </a>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Admin</a>
                </li>
                
            </ul>
        </div>
    </div>
</nav>



<!-- Categorías de comida (Carrusel) -->
<div class="container mt-4">
    <h4>Categorías</h4>
    <div id="categoryCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="row">
                    <div class="col-6">
                        <div class="card" style="background-image: url('img/pizza.webp'); height: 150px; background-size: cover;">
                            <div class="card-img-overlay d-flex align-items-center justify-content-center text-white">
                                Pizza
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card" style="background-image: url('img/hamburguesas.jpeg'); height: 150px; background-size: cover;">
                            <div class="card-img-overlay d-flex align-items-center justify-content-center text-white">
                                Hamburguesas
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="row">
                    <div class="col-6">
                        <div class="card" style="background-image: url('img/tacos.jpeg'); height: 150px; background-size: cover;">
                            <div class="card-img-overlay d-flex align-items-center justify-content-center text-white">
                                Tacos
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card" style="background-image: url('https://images.unsplash.com/photo-1499028344343-cd173ffc68a9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDJ8fHZlZ2V0YXJpYW4lMjBmb29kfGVufDB8fHx8MTY5NDY5NzU0Mw&ixlib=rb-1.2.1&q=80&w=400'); height: 150px; background-size: cover;">
                            <div class="card-img-overlay d-flex align-items-center justify-content-center text-white">
                                Vegetariana
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- Lista de Restaurantes -->
<div class="container mt-4">
    <h4>Restaurantes Disponibles</h4>
    <div class="row">
        <?php foreach ($restaurantes as $restaurante): ?>
        <div class="col-md-4">
            <div class="card restaurant-card">
                <a href="menu.php?restaurante_id=<?= $restaurante['id'] ?>">
                    <img src="restaurantes/<?= $restaurante['carpeta'] ?>/img/<?= $restaurante['imagen'] ?>" class="card-img-top" alt="<?= $restaurante['nombre'] ?>">
                    <div class="restaurant-info">
                        <h5><?= $restaurante['nombre'] ?></h5>
                        <p><?= $restaurante['descripcion'] ?></p>
                    </div>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Pie de Página -->
<footer class="footer">
    <p>&copy; 2024 Cero Distancia. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
