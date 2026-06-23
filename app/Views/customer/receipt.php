<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container" style="min-height: 85vh; padding-top: 30px;">
    
    <div class="receipt-wrapper">
        
        <div class="receipt-center">
            <div class="receipt-brand">BURJO<span>MODERN</span></div>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Jl. Raya PWL Burjo No. 10, Semarang</p>
            <p style="font-size: 0.75rem; color: var(--text-muted);">Telp: 0812-3456-7890</p>
        </div>

        <!-- Order Metadata -->
        <div class="receipt-divider"></div>
        
        <div class="receipt-row">
            <span>No. Pesanan:</span>
            <strong>#BRJ-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></strong>
        </div>
        <div class="receipt-row">
            <span>Tanggal:</span>
            <span><?= date('d M Y, H:i', strtotime($order['created_at'])) ?> WIB</span>
        </div>
        <div class="receipt-row">
            <span>Nama Pelanggan:</span>
            <strong><?= esc($order['customer_name']) ?></strong>
        </div>
        <div class="receipt-row" style="font-size: 1.05rem;">
            <span>Nomor Meja:</span>
            <strong style="color: var(--primary);">Meja <?= esc($order['table_number']) ?></strong>
        </div>

        <div class="receipt-divider"></div>

        <!-- Ordered Items List -->
        <div style="margin: 15px 0;">
            <p style="font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 10px;">Daftar Pesanan:</p>
            
            <?php foreach ($items as $item): ?>
                <div class="receipt-item-row">
                    <div class="receipt-item-top">
                        <span><?= esc($item['menu_name']) ?> <span style="font-weight: normal; color: var(--text-muted);">x<?= esc($item['quantity']) ?></span></span>
                        <span>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                    </div>
                    <div class="receipt-item-desc">
                        <span>Rp <?= number_format($item['price'], 0, ',', '.') ?> per porsi</span>
                    </div>
                    <?php if (!empty($item['notes'])): ?>
                        <div class="receipt-item-note">
                            * Catatan: "<?= esc($item['notes']) ?>"
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="receipt-divider"></div>

        <!-- Financial Summary -->
        <div class="receipt-row">
            <span>Subtotal:</span>
            <span>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
        </div>
        <div class="receipt-row">
            <span>Pajak (0%):</span>
            <span>Rp 0</span>
        </div>
        <div class="receipt-row total">
            <span>Total Pembayaran:</span>
            <strong>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></strong>
        </div>

        <div class="receipt-divider"></div>

        <!-- Status Summary -->
        <div class="receipt-row">
            <span>Metode Bayar:</span>
            <span class="badge badge-<?= $order['payment_method'] ?>"><?= strtoupper($order['payment_method']) ?></span>
        </div>
        <div class="receipt-row">
            <span>Status Bayar:</span>
            <span class="badge badge-<?= $order['payment_status'] == 'paid' ? 'paid' : 'unpaid' ?>">
                <?= $order['payment_status'] == 'paid' ? 'SUDAH BAYAR' : 'BELUM BAYAR (BAYAR KASIR)' ?>
            </span>
        </div>
        <div class="receipt-row">
            <span>Status Pesanan:</span>
            <span class="badge badge-<?= $order['order_status'] ?>">
                <?php 
                    if ($order['order_status'] == 'pending') echo 'Diterima (Antrean)';
                    elseif ($order['order_status'] == 'cooking') echo 'Sedang Dimasak';
                    elseif ($order['order_status'] == 'completed') echo 'Selesai Disajikan';
                    elseif ($order['order_status'] == 'cancelled') echo 'Dibatalkan';
                ?>
            </span>
        </div>

        <!-- Thank you footer -->
        <div class="receipt-thankyou">
            <p>Terima kasih atas pesanan Anda!</p>
            <p style="margin-top: 2px;">Silakan tunjukkan struk ini ke kasir jika belum membayar,</p>
            <p>atau tunggu makanan Anda disajikan di meja.</p>
        </div>

        <!-- Actions -->
        <button type="button" class="btn-print" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Cetak Struk ( thermal )
        </button>
        <a href="<?= base_url() ?>" class="btn-light" style="display: block; text-align: center; margin-top: 10px; font-size: 0.9rem;">
            <i class="fa-solid fa-rotate-left"></i> Kembali ke Halaman Utama
        </a>

    </div>

</div>

<?= $this->endSection() ?>
