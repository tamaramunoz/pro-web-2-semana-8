// Clase que representa el sistema de objetos de cada producto
class Product {
  constructor(id, nombre, price, discount, category, picture) {
    this.id = id;
    this.nombre = nombre;
    this.price = price;
    this.discount = discount;
    this.category = category;
    this.picture = picture;
  }

  calculateFinalPrice() {
    if (this.discount > 0) {
      return this.price * (1 - this.discount / 100);
    }
    return this.price;
  }

  createHTMLCard() {
    const card = document.createElement("div");
    card.className = "product-card";
    
    const tieneDescuento = this.discount > 0;
    const precioMostrar = this.calculateFinalPrice();

    card.innerHTML = `
      <div class="image">
        <img src="assets/${this.picture}" alt="imagen del producto" />
      </div>
      <h3 class="title-product">${this.nombre}</h3>
      <hr />
      <p class="category">Categoría: ${this.category}</p>
      <p class="price">
          ${tieneDescuento ? `<span class="original-price">$${this.price}</span>` : ""}
          <span class="final-price">$${precioMostrar}</span>
          ${tieneDescuento ? `<span class="badge">${this.discount}% OFF</span>` : ""}
      </p>
      <div class="button-container"> 
        <button class="add-to-cart-btn" data-id="${this.id}">Agregar al Carrito</button>
      </div>
    `;
    return card;
  }
}

// inventario inicial de la tienda
const stocktaking = [
  new Product(1, "Nintendo Switch 2", 539990, 10, "Consolas", "nintendo.jpeg"),
  new Product(2, "PlayStation 5 Slim ", 569990, 10, "Consolas", "play.png"),
  new Product(3, "Acer Nitro Lite 16", 819990, 10, "Electrónica", "acer.png"),
  new Product(4, "Asus TUF 15", 649990, 0, "Electrónica", "tuf.png"),
  new Product(5, "Mouse Inalámbrico", 37990, 15, "Accesorios", "mouse.jpg"),
  new Product(6, "Monitor Gamer 27'", 101990, 0, "Electrónica", "monitor.png"),
  new Product(7, "Apple iPad Air", 799990, 0, "Electrónica", "ipad.png"),
  new Product(8, "Teclado Redragon", 38990, 0, "Accesorios", "teclado.png")
];