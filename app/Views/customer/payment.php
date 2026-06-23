<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container" style="min-height: 70vh;">
    
    <div class="checkout-card" style="max-width: 550px;">
        
        <?php if ($order['payment_method'] === 'qris'): ?>
            <!-- QRIS Payment screen -->
            <div class="qris-card">
                <div class="checkout-header" style="border-bottom: none; margin-bottom: 5px; padding-bottom: 0;">
                    <h2><i class="fa-solid fa-qrcode"></i> Pembayaran QRIS</h2>
                    <p class="checkout-subtitle">Scan QRIS di bawah ini menggunakan aplikasi e-wallet Anda.</p>
                </div>

                <!-- QRIS Logo & Metadata -->
                <div style="background-color: #f8f9fa; border-radius: 12px; padding: 12px; margin-top: 15px;">
                    <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Total Tagihan</div>
                    <div style="font-size: 1.8rem; font-family: var(--font-heading); font-weight: 800; color: var(--primary);">
                        Rp <?= number_format($order['total_price'], 0, ',', '.') ?>
                    </div>
                </div>

                <!-- Display QRIS Barcode -->
                <div class="qris-barcode-wrapper">
                    <div class="qris-logo">QRIS<span>.</span></div>
                    <img src="<?= base_url('images/qris_mockup.png') ?>" alt="Barcode QRIS" class="qris-barcode">
                    <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; margin-top: 5px;">NMI: ID102026061803126</div>
                </div>

                <!-- Countdown Timer -->
                <div class="timer-display">
                    <i class="fa-regular fa-clock"></i> Sisa Waktu Bayar: <span id="countdown">05:00</span>
                </div>

                <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 15px;">
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 15px;">
                        <i class="fa-solid fa-circle-info"></i> Gunakan tombol simulasi di bawah ini untuk mensimulasikan pembayaran berhasil dari e-wallet.
                    </p>
                    
                    <form action="<?= base_url('customer/simulate_payment/' . $order['id']) ?>" method="POST">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-simulate">
                            <i class="fa-solid fa-circle-check"></i> Simulasi Bayar Sukses
                        </button>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <!-- Cash Payment screen -->
            <div style="text-align: center; padding: 20px 0;">
                <div class="checkout-header">
                    <h2><i class="fa-solid fa-money-bill-wave" style="color: var(--success);"></i> Pembayaran Cash / Tunai</h2>
                    <p class="checkout-subtitle">Silakan selesaikan pembayaran langsung di kasir.</p>
                </div>

                <div class="cust-info-block" style="text-align: left; margin: 25px 0;">
                    <h4 style="margin-bottom: 10px; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">Instruksi Pembayaran:</h4>
                    <ul style="padding-left: 20px; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px;">
                        <li>Kunjungi meja kasir Burjo Modern.</li>
                        <li>Sebutkan nama pemesan: <strong><?= esc($order['customer_name']) ?></strong> atau nomor meja: <strong>Meja <?= esc($order['table_number']) ?></strong>.</li>
                        <li>Tunjukkan pesanan Anda senilai <strong>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></strong>.</li>
                        <li>Setelah membayar, mintalah kasir untuk memproses hidangan Anda.</li>
                    </ul>
                </div>

                <div style="margin-top: 30px;">
                    <a href="<?= base_url('customer/receipt/' . $order['id']) ?>" class="btn-primary" style="display: inline-block; padding: 12px 35px; text-align: center; gap: 8px;">
                        <i class="fa-solid fa-receipt"></i> Lihat Struk Pesanan
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if ($order['payment_method'] === 'qris'): ?>
<script>
    // Countdown Timer logic
    let duration = 300; // 5 minutes in seconds
    const countdownEl = document.getElementById("countdown");
    
    const timer = setInterval(() => {
        let minutes = Math.floor(duration / 60);
        let seconds = duration % 60;
        
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        
        countdownEl.textContent = minutes + ":" + seconds;
        
        if (duration <= 0) {
            clearInterval(timer);
            countdownEl.textContent = "EXPIRED";
            alert("Waktu pembayaran QRIS telah habis. Silakan buat pesanan baru.");
            window.location.href = "<?= base_url() ?>";
        }
        
        duration--;
    }, 1000);
</script>
<?php endif; ?>
<script>
    // Clear the cart on browser storage since order has been created successfully
    localStorage.removeItem("burjo_cart");
    localStorage.removeItem("burjo_table");
</script>
<?= $this->endSection() ?>
