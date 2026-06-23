// Burjo Cart Management JavaScript

let cart = [];
let selectedTable = null;

// Initialize on page load
document.addEventListener("DOMContentLoaded", () => {
    loadCart();
    renderCart();
    initTableSelection();
});

// Load cart from LocalStorage
function loadCart() {
    const savedCart = localStorage.getItem("burjo_cart");
    if (savedCart) {
        try {
            cart = JSON.parse(savedCart);
        } catch (e) {
            cart = [];
        }
    }
    const savedTable = localStorage.getItem("burjo_table");
    if (savedTable) {
        selectedTable = parseInt(savedTable);
    }
}

// Save cart to LocalStorage
function saveCart() {
    localStorage.setItem("burjo_cart", JSON.stringify(cart));
    if (selectedTable) {
        localStorage.setItem("burjo_table", selectedTable);
    } else {
        localStorage.removeItem("burjo_table");
    }
}

// Add item to cart
function addToCart(id, name, price) {
    const existingItem = cart.find(item => item.id === id);
    if (existingItem) {
        existingItem.qty += 1;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            qty: 1,
            notes: ""
        });
    }
    saveCart();
    renderCart();
    
    // Pulse animation for the cart title
    const cartTitle = document.getElementById("cart-title");
    if (cartTitle) {
        cartTitle.style.transform = "scale(1.05)";
        setTimeout(() => {
            cartTitle.style.transform = "scale(1)";
        }, 200);
    }
}

// Change quantity of item in cart
function changeQty(id, delta) {
    const itemIndex = cart.findIndex(item => item.id === id);
    if (itemIndex > -1) {
        cart[itemIndex].qty += delta;
        if (cart[itemIndex].qty <= 0) {
            cart.splice(itemIndex, 1);
        }
        saveCart();
        renderCart();
    }
}

// Update note for an item
function updateNote(id, noteText) {
    const item = cart.find(item => item.id === id);
    if (item) {
        item.notes = noteText;
        saveCart();
    }
}

// Remove item from cart
function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    saveCart();
    renderCart();
}

// Initialize Table Selection Input
function initTableSelection() {
    const tableInput = document.getElementById("table-number-input");
    if (tableInput) {
        // Mark with saved state if exists
        if (selectedTable) {
            tableInput.value = selectedTable;
        }
        
        tableInput.addEventListener("input", () => {
            const val = tableInput.value.trim();
            selectedTable = val ? parseInt(val) : null;
            saveCart();
        });
    }
}

// Format Rupiah Currency helper
function formatRupiah(number) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
}

// Clear cart fully (called on successful checkout receipt page)
function clearCart() {
    cart = [];
    selectedTable = null;
    localStorage.removeItem("burjo_cart");
    localStorage.removeItem("burjo_table");
    renderCart();
}

// Render cart contents to the DOM
function renderCart() {
    const cartList = document.getElementById("cart-items-list");
    const totalDisplay = document.getElementById("cart-total-price");
    const checkoutBtn = document.getElementById("btn-checkout");
    const cartCount = document.getElementById("cart-badge-count");
    const formCartData = document.getElementById("hidden-cart-data");
    const formTableInput = document.getElementById("table-number-input");

    if (!cartList) return; // Not on customer menu page

    if (cart.length === 0) {
        cartList.innerHTML = `
            <div class="cart-empty">
                <div class="cart-empty-icon">🛒</div>
                <p>Keranjang belanja masih kosong.</p>
                <small>Silakan pilih menu makanan & minuman lezat kami!</small>
            </div>
        `;
        totalDisplay.textContent = formatRupiah(0);
        if (checkoutBtn) checkoutBtn.disabled = true;
        if (cartCount) cartCount.style.display = "none";
        return;
    }

    if (checkoutBtn) checkoutBtn.disabled = false;

    // Render items list
    let html = "";
    let totalPrice = 0;
    let totalItems = 0;

    cart.forEach(item => {
        const itemTotal = item.price * item.qty;
        totalPrice += itemTotal;
        totalItems += item.qty;

        html += `
            <div class="cart-item" data-id="${item.id}">
                <div class="cart-item-header">
                    <span class="cart-item-name">${item.name}</span>
                    <span class="cart-item-price">${formatRupiah(itemTotal)}</span>
                </div>
                <div class="cart-item-actions">
                    <div class="qty-controller">
                        <button type="button" class="qty-btn" onclick="changeQty(${item.id}, -1)">-</button>
                        <span class="qty-val">${item.qty}</span>
                        <button type="button" class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
                    </div>
                    <button type="button" class="cart-item-remove" onclick="removeFromCart(${item.id})">
                        Hapus
                    </button>
                </div>
                <input type="text" class="cart-item-note-input" 
                       placeholder="Tambah catatan (contoh: pedas, es dikit)..." 
                       value="${item.notes}" 
                       oninput="updateNote(${item.id}, this.value)">
            </div>
        `;
    });

    cartList.innerHTML = html;
    totalDisplay.textContent = formatRupiah(totalPrice);

    if (cartCount) {
        cartCount.textContent = totalItems;
        cartCount.style.display = "inline-block";
    }

    // Set form hidden values
    if (formCartData) {
        formCartData.value = JSON.stringify(cart);
    }
    if (formTableInput) {
        formTableInput.value = selectedTable || "";
    }
}

// Intercept checkout form submission for client-side validations
function validateCheckout(event) {
    const custName = document.getElementById("cust-name-input").value.trim();
    
    if (cart.length === 0) {
        alert("Keranjang belanja Anda kosong!");
        event.preventDefault();
        return false;
    }

    if (!custName) {
        alert("Silakan masukkan nama Anda!");
        document.getElementById("cust-name-input").focus();
        event.preventDefault();
        return false;
    }

    if (!selectedTable || selectedTable < 1) {
        alert("Silakan masukkan nomor meja Anda!");
        const tableInput = document.getElementById("table-number-input");
        if (tableInput) {
            tableInput.focus();
            tableInput.scrollIntoView({ behavior: 'smooth' });
        }
        event.preventDefault();
        return false;
    }

    return true;
}
