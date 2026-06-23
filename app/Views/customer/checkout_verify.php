<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container" style="min-height: 70vh;">
    
    <div class="checkout-card">
        
        <div class="checkout-header">
            <h2><i class="fa-solid fa-clipboard-check"></i> Verifikasi Pesanan Anda</h2>
            <p class="checkout-subtitle">Harap periksa kembali detail pesanan makanan, minuman, dan nomor meja Anda sebelum checkout.</p>
        </div>

        <form action="<?= base_url('customer/confirm_order') ?>" method="POST">
            <?= csrf_field() ?>
            <!-- Hidden Fields for Database -->
            <input type="hidden" name="customer_name" value="<?= esc($customer_name) ?>">
            <input type="hidden" name="table_number" value="<?= esc($table_number) ?>">
            <input type="hidden" name="total_price" value="<?= esc($total_price) ?>">
            <input type="hidden" name="cart_data" value="<?= esc(json_encode($cart_items)) ?>">

            <!-- Customer Info Summary -->
            <div class="cust-info-block">
                <div class="cust-info-grid">
                    <div>
                        <div class="cust-info-label"><i class="fa-solid fa-user"></i> Nama Pelanggan</div>
                        <div class="cust-info-value"><?= esc($customer_name) ?></div>
                    </div>
                    <div>
                        <div class="cust-info-label"><i class="fa-solid fa-chair"></i> Nomor Meja</div>
                        <div class="cust-info-value" style="font-size: 1.25rem;">Meja <?= esc($table_number) ?></div>
                    </div>
                </div>
            </div>

            <!-- Items checklist -->
            <h3 style="margin-bottom: 15px; font-size: 1.1rem; border-bottom: 2px solid var(--border-color); padding-bottom: 8px;">
                <i class="fa-solid fa-utensils"></i> Detail Item Pesanan
            </h3>
            <div class="verification-summary-list">
                <?php foreach ($cart_items as $item): ?>
                    <div class="verification-item">
                        <div class="verification-item-details">
                            <div class="verification-item-name">
                                <?= esc($item['name']) ?> 
                                <span style="color: var(--text-muted); font-size: 0.85rem; font-weight: normal; margin-left: 5px;">
                                    x<?= esc($item['qty']) ?>
                                </span>
                            </div>
                            <?php if (!empty($item['notes'])): ?>
                                <div class="verification-item-notes">
                                    <i class="fa-solid fa-comment-dots"></i> Catatan: "<?= esc($item['notes']) ?>"
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="verification-item-pricing">
                            Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Total Price Summary -->
            <div class="receipt-row total" style="margin-bottom: 30px; font-size: 1.2rem; display: flex; justify-content: space-between; font-weight: 800; border-top: 2px solid var(--border-color); padding-top: 15px;">
                <span>Total Pembayaran</span>
                <span style="color: var(--primary);">Rp <?= number_format($total_price, 0, ',', '.') ?></span>
            </div>

            <!-- Payment Method Selection -->
            <div class="payment-selector">
                <div class="payment-title"><i class="fa-solid fa-wallet"></i> Pilih Metode Pembayaran</div>
                <div class="payment-options-grid">
                    
                    <!-- QRIS Option -->
                    <label class="payment-card selected" id="payment-card-qris">
                        <input type="radio" name="payment_method" value="qris" checked onclick="selectPayment('qris')">
                        <div class="payment-icon"><i class="fa-solid fa-qrcode"></i></div>
                        <div class="payment-label">QRIS</div>
                        <div class="payment-desc">Bayar instan pakai e-wallet/m-banking (OVO, GoPay, Dana, LinkAja, BCA, dll)</div>
                    </label>

                    <!-- Cash Option -->
                    <label class="payment-card" id="payment-card-cash">
                        <input type="radio" name="payment_method" value="cash" onclick="selectPayment('cash')">
                        <div class="payment-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
                        <div class="payment-label">Cash / Tunai</div>
                        <div class="payment-desc">Bayar langsung di kasir sebelum atau sesudah pesanan selesai disajikan</div>
                    </label>

                </div>
            </div>

            <!-- Action buttons -->
            <div class="btn-group">
                <a href="<?= base_url() ?>" class="btn-light" style="flex: 1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Menu
                </a>
                <button type="submit" class="btn-primary" style="flex: 2; font-size: 1.05rem; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fa-solid fa-circle-check"></i> Proses & Pesan Sekarang
                </button>
            </div>

        </form>

    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Visual toggling for payment selection cards
    function selectPayment(method) {
        const qrisCard = document.getElementById("payment-card-qris");
        const cashCard = document.getElementById("payment-card-cash");

        if (method === "qris") {
            qrisCard.classList.add("selected");
            cashCard.classList.remove("selected");
        } else {
            cashCard.classList.add("selected");
            qrisCard.classList.remove("selected");
        }
    }
</script>
<?= $this->endSection() ?>
