<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero -->
<section class="hero">
    <div class="container">
        <h1 class="hero-title"><i class="fa-solid fa-fire"></i> Warung Burjo Modern</h1>
        <p class="hero-subtitle">Nikmati sajian khas warmindo legendaris yang lezat, higienis, dan cepat saji langsung dari meja Anda.</p>
    </div>
</section>

<!-- Main Workspace -->
<div class="container">
    
    <!-- Category Filters -->
    <div class="filters-wrapper">
        <button type="button" class="filter-btn active" onclick="filterMenu('all', this)">
            <i class="fa-solid fa-list"></i> Semua
        </button>
        <button type="button" class="filter-btn" onclick="filterMenu('makanan', this)">
            <i class="fa-solid fa-bowl-rice"></i> Makanan
        </button>
        <button type="button" class="filter-btn" onclick="filterMenu('minuman', this)">
            <i class="fa-solid fa-whiskey-glass"></i> Minuman
        </button>
    </div>

    <div class="main-grid">
        
        <!-- Left Side: Menu List -->
        <div>
            <div class="menu-section" id="menu-container">
                <?php if (empty($menus)): ?>
                    <div style="text-align: center; grid-column: 1/-1; padding: 40px; background: white; border-radius: 20px; border: 1px solid #e9ecef;">
                        <p style="color: var(--text-muted); font-size: 1.1rem;">Belum ada menu yang tersedia.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($menus as $item): ?>
                        <div class="menu-card" data-category="<?= $item['type'] ?>">
                            <div class="menu-img-container">
                                <span class="menu-badge <?= $item['type'] ?>">
                                    <?= $item['type'] == 'makanan' ? 'Makanan' : 'Minuman' ?>
                                </span>
                                <?php 
                                    $imagePath = 'uploads/menus/' . $item['image'];
                                    if (!empty($item['image']) && file_exists(FCPATH . $imagePath)): 
                                ?>
                                    <img src="<?= base_url($imagePath) ?>" alt="<?= esc($item['name']) ?>" class="menu-img">
                                <?php else: ?>
                                    <div class="menu-img-placeholder">
                                        <?php if ($item['type'] == 'makanan'): ?>
                                            <span class="menu-placeholder-icon">🍜</span>
                                        <?php else: ?>
                                            <span class="menu-placeholder-icon">🥤</span>
                                        <?php endif; ?>
                                        <small style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.8;">
                                            <?= esc($item['name']) ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="menu-details">
                                <h3 class="menu-name"><?= esc($item['name']) ?></h3>
                                <p class="menu-desc"><?= esc($item['description'] ?? 'Menu spesial ala burjo modern.') ?></p>
                                <div class="menu-bottom">
                                    <span class="menu-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                                    <button type="button" class="btn-add-cart" onclick="addToCart(<?= $item['id'] ?>, '<?= esc($item['name']) ?>', <?= $item['price'] ?>)">
                                        <i class="fa-solid fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Side: Sidebar Cart & Checkout Selection -->
        <aside class="cart-panel">
            <h2 class="cart-title" id="cart-title">
                <i class="fa-solid fa-cart-shopping" style="color: var(--secondary);"></i> 
                Keranjang Belanja
                <span id="cart-badge-count" style="display: none; background-color: var(--danger); color: white; border-radius: 50%; width: 22px; height: 22px; font-size: 0.75rem; text-align: center; line-height: 22px; margin-left: auto;">0</span>
            </h2>

            <div class="cart-items-list" id="cart-items-list">
                <!-- Javascript populated -->
            </div>

            <!-- Checkout Form -->
            <form action="<?= base_url('customer/checkout') ?>" method="POST" onsubmit="return validateCheckout(event)">
                <?= csrf_field() ?>
                <input type="hidden" name="cart_data" id="hidden-cart-data">
                
                <div style="margin-top: 15px;">
                    <label for="table-number-input" style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 5px; color: var(--primary);">
                        <i class="fa-solid fa-chair"></i> Nomor Meja
                    </label>
                    <input type="number" name="table_number" id="table-number-input" class="input-cust-name" placeholder="Masukkan nomor meja Anda..." min="1" required>
                </div>
                
                <div style="margin-top: 15px;">
                    <label for="cust-name-input" style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 5px; color: var(--primary);">
                        <i class="fa-solid fa-user"></i> Nama Pemesan
                    </label>
                    <input type="text" name="customer_name" id="cust-name-input" class="input-cust-name" placeholder="Masukkan nama Anda..." required>
                </div>

                <div class="cart-total-section">
                    <div class="cart-total-row">
                        <span>Total Bayar:</span>
                        <span class="cart-total-price" id="cart-total-price">Rp 0</span>
                    </div>
                    <button type="submit" class="btn-checkout" id="btn-checkout" disabled>
                        <i class="fa-solid fa-square-check"></i> Periksa Pesanan
                    </button>
                </div>
            </form>
        </aside>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Filter menu by category (all, makanan, minuman)
    function filterMenu(category, button) {
        // Toggle active button
        const buttons = document.querySelectorAll(".filter-btn");
        buttons.forEach(btn => btn.classList.remove("active"));
        button.classList.add("active");

        // Filter cards
        const cards = document.querySelectorAll(".menu-card");
        cards.forEach(card => {
            const cardCat = card.getAttribute("data-category");
            if (category === "all" || cardCat === category) {
                card.style.display = "flex";
                // Simple fade animation
                card.style.opacity = "0";
                setTimeout(() => {
                    card.style.opacity = "1";
                }, 50);
            } else {
                card.style.display = "none";
            }
        });
    }
</script>
<?= $this->endSection() ?>
