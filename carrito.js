// Inicializar el carrito
let carrito = [];

// Función para actualizar el ícono del carrito en el navbar
function actualizarIconoCarrito() {
    const carritoCantidad = document.getElementById("carrito-cantidad");
    if (carritoCantidad) {
        carritoCantidad.innerText = carrito.reduce((acc, item) => acc + item.cantidad, 0);
    }
}

// Función para agregar un producto al carrito
function agregarAlCarrito(id, nombre, precio, imagen) {
    const productoExistente = carrito.find(item => item.id === id);

    if (productoExistente) {
        productoExistente.cantidad += 1;
    } else {
        carrito.push({ id, nombre, precio, imagen, cantidad: 1 });
    }

    actualizarIconoCarrito();
    mostrarCarrito(); // Llamar a esta función para mostrar el carrito al agregar
}

// Función para mostrar el carrito en un modal
function mostrarCarrito() {
    const modalBody = document.getElementById("carrito-modal-body");
    modalBody.innerHTML = '';

    if (carrito.length === 0) {
        modalBody.innerHTML = '<p>El carrito está vacío.</p>';
        return;
    }

    carrito.forEach(item => {
        const productoDiv = document.createElement('div');
        productoDiv.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-3');
        productoDiv.innerHTML = `
            <div>
                <img src="${item.imagen}" alt="${item.nombre}" style="width: 50px; height: 50px; object-fit: cover;">
                <strong>${item.nombre}</strong>
            </div>
            <div>
                <button class="btn btn-sm btn-danger" onclick="cambiarCantidad(${item.id}, -1)">-</button>
                <span class="mx-2">${item.cantidad}</span>
                <button class="btn btn-sm btn-success" onclick="cambiarCantidad(${item.id}, 1)">+</button>
            </div>
            <div>
                $${(item.precio * item.cantidad).toFixed(2)}
            </div>
        `;
        modalBody.appendChild(productoDiv);
    });

    // Actualizar el total
    const total = carrito.reduce((acc, item) => acc + item.precio * item.cantidad, 0);
    document.getElementById("carrito-total").innerText = `Total: $${total.toFixed(2)}`;
}

// Función para cambiar la cantidad de un producto en el carrito
function cambiarCantidad(id, cantidad) {
    const producto = carrito.find(item => item.id === id);
    if (producto) {
        producto.cantidad += cantidad;
        if (producto.cantidad <= 0) {
            carrito = carrito.filter(item => item.id !== id);
        }
    }

    actualizarIconoCarrito();
    mostrarCarrito();
}

// Función para vaciar el carrito
function vaciarCarrito() {
    carrito = [];
    actualizarIconoCarrito();
    mostrarCarrito();
}

// Escuchar clicks en los botones de "Agregar al Carrito"
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        const id = parseInt(button.getAttribute('data-id'));
        const nombre = button.getAttribute('data-name');
        const precio = parseFloat(button.getAttribute('data-price'));
        const imagen = button.getAttribute('data-image');
        
        agregarAlCarrito(id, nombre, precio, imagen);
    });
});
