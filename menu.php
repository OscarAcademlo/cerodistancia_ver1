<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluye la conexión a la base de datos
include __DIR__ . '/db.php';

// Obtener el restaurante_id desde la URL
$restaurante_id = isset($_GET['restaurante_id']) ? (int)$_GET['restaurante_id'] : 0;

if ($restaurante_id <= 0) {
    die('Restaurante no especificado o ID inválido');
}

// Obtener la información del restaurante desde la base de datos
$stmt_restaurante = $pdo->prepare("SELECT * FROM restaurantes WHERE id = ?");
$stmt_restaurante->execute([$restaurante_id]);
$restaurante = $stmt_restaurante->fetch(PDO::FETCH_ASSOC);

if (!$restaurante) {
    die('Restaurante no encontrado');
}

// Obtener los productos del restaurante
$stmt_productos = $pdo->prepare("SELECT * FROM productos WHERE restaurante_id = ?");
$stmt_productos->execute([$restaurante_id]);
$productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Incluye jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <!-- Incluye Bootstrap JS -->
    <style>
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }
        .card-title {
            font-size: 1.1em;
            font-weight: bold;
        }
        .btn-success {
            margin-top: auto;
        }
    </style>
    <title><?= htmlspecialchars($restaurante['nombre'], ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body class='container mt-5'>

    <!-- Incluir el navbar -->
    <?php include __DIR__ . '/navbar.php'; ?>

    <h1><?= htmlspecialchars($restaurante['nombre'], ENT_QUOTES, 'UTF-8') ?></h1>
    <h2>Menú</h2>
    <div class='row'>
        <?php foreach ($productos as $producto): ?>
            <div class='col-md-4 mb-4 d-flex'>
                <div class='card w-100'>
                    <?php 
                    $imagePath = $producto['imagen'];
                    ?>
                    <img src="<?= $imagePath ?>" class='card-img-top' alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class='card-body'>
                        <h5 class='card-title'><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></h5>
                        <p class='card-text'><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p class='card-text'>$<?= number_format($producto['precio'], 2) ?></p>
                        <button class="btn btn-success add-to-cart" 
                            data-id="<?= $producto['id'] ?>" 
                            data-name="<?= $producto['nombre'] ?>" 
                            data-price="<?= $producto['precio'] ?>" 
                            data-description="<?= $producto['descripcion'] ?>" 
                            data-image="<?= $producto['imagen'] ?>">
                            Agregar al Carrito
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="carrito.js"></script>

    <!-- Modal para el carrito -->
    <div class="modal fade" id="carritoModal" tabindex="-1" aria-labelledby="carritoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="carritoModalLabel">Carrito de Compras</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="carrito-modal-body">
            <p>El carrito está vacío.</p>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <strong id="carrito-total">Total: $0.00</strong>
            <button type="button" class="btn btn-secondary" onclick="vaciarCarrito()">Vaciar Carrito</button>
            <button type="button" class="btn btn-primary" id="continuarCompra">Continuar con la Compra</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para completar la compra -->
    <div class="modal fade" id="compraModal" tabindex="-1" aria-labelledby="compraModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="compraModalLabel">Completar Datos para el Pedido</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="form-datos-pedido">
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" required>
              </div>
              <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" required>
              </div>
              <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="telefono" required>
              </div>
              <div class="mb-3">
                <label for="calle" class="form-label">Calle</label>
                <input type="text" class="form-control" id="calle" required>
              </div>
              <div class="mb-3">
                <label for="numero" class="form-label">Número</label>
                <input type="text" class="form-control" id="numero" required>
              </div>
              <div class="mb-3">
                <label for="barrio" class="form-label">Barrio</label>
                <input type="text" class="form-control" id="barrio" required>
              </div>
              <h5>Resumen del Pedido</h5>
              <div id="resumen-pedido"></div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="enviarWhatsApp">Enviar por WhatsApp</button>
          </div>
        </div>
      </div>
    </div>

    <script>
    $(document).ready(function() {
        // Agregar al carrito
        $('.add-to-cart').on('click', function () {
            let productId = $(this).data('id');
            let productName = $(this).data('name');
            let productPrice = $(this).data('price');
            let productDescription = $(this).data('description');
            let productImage = $(this).data('image');

            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

            let existingProduct = carrito.find(item => item.id === productId);

            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                carrito.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    description: productDescription,
                    image: productImage,
                    quantity: 1
                });
            }

            localStorage.setItem('carrito', JSON.stringify(carrito));
            alert('Producto agregado al carrito');
        });

        // Mostrar resumen del carrito en el nuevo modal
        $('#continuarCompra').on('click', function () {
            $('#compraModal').modal('show'); 
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            
            if (carrito.length === 0) {
                alert('El carrito está vacío, agrega productos antes de continuar.');
                return;
            }

            let resumenHTML = '<ul class="list-group">';
            let total = 0;

            carrito.forEach(item => {
                let subtotal = item.price * item.quantity;
                total += subtotal;
                resumenHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold">${item.name} x ${item.quantity}</div>
                            <small>${item.description}</small>
                        </div>
                        <span>$${subtotal.toFixed(2)}</span>
                    </li>`;
            });

            resumenHTML += `</ul><div class="mt-3 text-end"><strong>Total: $${total.toFixed(2)}</strong></div>`;
            $('#resumen-pedido').html(resumenHTML);
            $('#resumen-pedido').data('total', total); 
        });

        // Enviar la información por WhatsApp
        $('#enviarWhatsApp').on('click', function () {
            let nombre = $('#nombre').val();
            let apellido = $('#apellido').val();
            let telefono = $('#telefono').val();
            let calle = $('#calle').val();
            let numero = $('#numero').val();
            let barrio = $('#barrio').val();

            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            if (carrito.length === 0) {
                alert('El carrito está vacío, agrega productos antes de continuar.');
                return;
            }

            let mensaje = `Hola, soy ${nombre} ${apellido}. Quisiera hacer un pedido de:\n`;
            let total = 0;

            carrito.forEach(item => {
                let subtotal = item.price * item.quantity;
                total += subtotal;
                mensaje += `- ${item.quantity} x ${item.name} (${item.description}) - $${subtotal.toFixed(2)}\n`;
            });

            mensaje += `\nPor un valor total de: $${total.toFixed(2)}`;
            mensaje += `\nPara entregar en: ${calle} ${numero}, ${barrio}.`;
            mensaje += `\nTeléfono de contacto: ${telefono}`;

            let whatsappUrl = `https://wa.me/5492944682681?text=${encodeURIComponent(mensaje)}`;
            window.open(whatsappUrl, '_blank');
        });
    });
    </script>
</body>
</html>
