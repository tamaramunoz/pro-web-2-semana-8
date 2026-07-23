let carrito = [];

const searchInput = document.getElementById("product-search");
const searchButton = document.getElementById("search-btn");
const clearButton = document.getElementById("clear-btn");
const resultsContainer = document.getElementById("results-container");
const cartCounter = document.getElementById("cart-counter");
const notificationToast = document.getElementById("notification-toast");

function searchProducts() {
  const query = searchInput.value.toLowerCase().trim();
  resultsContainer.innerHTML = ""; 

  const filteredProducts = stocktaking.filter(producto => 
      producto.nombre.toLowerCase().includes(query) || 
      producto.category.toLowerCase().includes(query)
  );

  if (filteredProducts.length === 0) {
      resultsContainer.innerHTML = "<p class='no-results'>No se encontraron productos.</p>";
      return;
  }

  filteredProducts.forEach(producto => {
      const cardElement = producto.createHTMLCard();
      resultsContainer.appendChild(cardElement); 
  });

  vincularBotonesCarrito();
}

function notificationPush(mensaje) {
  notificationToast.textContent = mensaje;
  notificationToast.style.display = "block";
    
  setTimeout(() => {
    notificationToast.style.display = "none";
  }, 3000);
}

function cargarStockEnFormulario() {
  const selectProducto = document.getElementById("producto");
  
  if (selectProducto) {
    stocktaking.forEach(producto => {
      const option = document.createElement("option");

      option.value = producto.nombre; 
      option.textContent = `${producto.nombre} ($${producto.calculateFinalPrice().toLocaleString('es-CL')})`;
      selectProducto.appendChild(option);
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  searchProducts();
  cargarStockEnFormulario();
  
  setTimeout(() => {
    notificationPush("¡Promoción exclusiva! 15% de descuento usando el código WEB2.");
  }, 2000);
});

searchButton.addEventListener("click", searchProducts);

clearButton.addEventListener("click", () => {
    searchInput.value = "";
    searchProducts();
});

searchInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") searchProducts();
});

// vinculamos el carrito mediante fetch para la comunicación con el backend
function vincularBotonesCarrito() {
    const botones = document.querySelectorAll(".add-to-cart-btn");
    botones.forEach(boton => {
        boton.replaceWith(boton.cloneNode(true));
    });

    document.querySelectorAll(".add-to-cart-btn").forEach(boton => {
      boton.addEventListener("click", (e) => {
          const idProducto = parseInt(e.target.getAttribute("data-id"));
          const productoSeleccionado = stocktaking.find(p => p.id === idProducto);
          
          if (productoSeleccionado) {
              fetch(`carrito_sesion.php?action=add&id=${idProducto}`)
                  .then(response => {
                      if (response.ok) {
                          let conteoActual = parseInt(cartCounter.textContent) || 0;
                          cartCounter.textContent = conteoActual + 1;
                          notificationPush(`¡${productoSeleccionado.nombre} fue añadido al carrito exitosamente!`);
                      } else {
                          console.error("El servidor rechazó la operación.");
                      }
                  })
                  .catch(error => {
                      console.error("Error en la comunicación con el servidor PHP:", error);
                  });
          }
      });
    });
}