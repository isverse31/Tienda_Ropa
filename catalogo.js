async function obtenerPrendas() {
    try {
        const response = await fetch('ropa-api.php');

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const prendas = await response.json();
        const catalogo = document.getElementById('catalogo');
        catalogo.innerHTML = ''; // Limpiar cualquier contenido existente

        prendas.forEach(prenda => {
            const productoDiv = document.createElement('div');
            productoDiv.classList.add('producto');
            productoDiv.id = `producto-${prenda.id}`;

            const img = document.createElement('img');
            img.src = prenda.imagen_url;
            img.alt = prenda.nombre;

            const nombre = document.createElement('h3');
            nombre.textContent = prenda.nombre;

            const precio = document.createElement('p');
            const precioNumero = parseFloat(prenda.precio);
            if (isNaN(precioNumero)) {
                precio.textContent = 'Precio no disponible';
            } else {
                precio.textContent = `$${precioNumero.toFixed(2)}`;
            }

            const button = document.createElement('button');
            button.textContent = 'Añadir al carrito';
            button.onclick = () => añadirAlCarrito(prenda);

            productoDiv.appendChild(img);
            productoDiv.appendChild(nombre);
            productoDiv.appendChild(precio);
            productoDiv.appendChild(button);

            catalogo.appendChild(productoDiv);
        });
    } catch (error) {
        console.error('Error al realizar la solicitud:', error);
        mostrarMensajeError(`Error al realizar la solicitud: ${error.message}`);
    }
}

function mostrarMensaje(mensaje, tipo = 'exito') {
    const mensajeDiv = document.getElementById('mensaje');
    mensajeDiv.textContent = mensaje;
    mensajeDiv.className = `mensaje ${tipo}`;
    mensajeDiv.classList.add('mostrar');
    setTimeout(() => {
        mensajeDiv.classList.remove('mostrar');
    }, 3000); // Ocultar el mensaje después de 3 segundos
}

function mostrarMensajeError(mensaje) {
    mostrarMensaje(mensaje, 'error');
}

function añadirAlCarrito(prenda) {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    carrito.push(prenda);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    mostrarMensaje('Artículo agregado al carrito');
}

document.getElementById('ver-carrito').onclick = () => {
    window.open('carrito.html', 'Carrito', 'width=600,height=600');
};

window.onload = obtenerPrendas;
