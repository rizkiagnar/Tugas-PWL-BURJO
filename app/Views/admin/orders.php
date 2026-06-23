<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div style="margin-bottom: 25px;">
    <h2><i class="fa-solid fa-list-check"></i> Kelola Pesanan Pelanggan</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Pantau antrean, masak hidangan, konfirmasi pembayaran, dan selesaikan pesanan meja secara real-time.</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    
    <div class="stat-card">
        <div class="stat-icon-wrapper stat-icon-pending">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div class="stat-info">
            <span class="stat-val"><?= $stats['pending'] ?></span>
            <span class="stat-label">Pending</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrapper stat-icon-cooking">
            <i class="fa-solid fa-fire-burner"></i>
        </div>
        <div class="stat-info">
            <span class="stat-val"><?= $stats['cooking'] ?></span>
            <span class="stat-label">Dimasak</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrapper stat-icon-completed">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <span class="stat-val"><?= $stats['completed'] ?></span>
            <span class="stat-label">Selesai</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrapper stat-icon-revenue">
            <i class="fa-solid fa-wallet"></i>
        </div>
        <div class="stat-info">
            <span class="stat-val" style="font-size: 1.1rem; line-height: 1.6;">Rp <?= number_format($stats['revenue'], 0, ',', '.') ?></span>
            <span class="stat-label">Omzet (Lunas)</span>
        </div>
    </div>

</div>

<!-- Active Orders Table -->
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 1.2rem; color: var(--primary);"><i class="fa-solid fa-clock"></i> Antrean Pesanan Aktif</h3>
        <button type="button" class="btn-light" onclick="window.location.reload();" style="padding: 6px 12px; font-size: 0.8rem; display: flex; align-items: center; gap: 5px;">
            <i class="fa-solid fa-rotate"></i> Segarkan Halaman
        </button>
    </div>

    <div class="orders-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Waktu / Meja</th>
                    <th>Nama Pelanggan</th>
                    <th>Detail Item & Catatan</th>
                    <th>Total Bayar</th>
                    <th>Metode / Status Bayar</th>
                    <th>Status Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <i class="fa-solid fa-circle-exclamation" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                            Tidak ada pesanan aktif saat ini.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <!-- Order ID -->
                            <td>
                                <strong style="color: var(--primary);">#BRJ-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></strong>
                            </td>
                            <!-- Time & Table -->
                            <td>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?= date('H:i', strtotime($order['created_at'])) ?> WIB</div>
                                <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem;">Meja <?= esc($order['table_number']) ?></div>
                            </td>
                            <!-- Customer Name -->
                            <td>
                                <strong><?= esc($order['customer_name']) ?></strong>
                            </td>
                            <!-- Items Details -->
                            <td>
                                <div style="font-size: 0.85rem; max-width: 280px;">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div style="margin-bottom: 5px; line-height: 1.3;">
                                            • <strong><?= esc($item['menu_name']) ?></strong> 
                                            <span style="color: var(--text-muted);">x<?= esc($item['quantity']) ?></span>
                                            <?php if (!empty($item['notes'])): ?>
                                                <div style="font-size: 0.75rem; color: var(--danger); font-style: italic; margin-left: 8px;">
                                                    * Catatan: "<?= esc($item['notes']) ?>"
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <!-- Total Price -->
                            <td>
                                <strong style="font-family: var(--font-heading); color: var(--primary);">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></strong>
                            </td>
                            <!-- Payment Status -->
                            <td>
                                <span class="badge badge-<?= $order['payment_method'] ?>" style="margin-bottom: 5px;"><?= strtoupper($order['payment_method']) ?></span>
                                
                                <form action="<?= base_url('admin/update_payment/' . $order['id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <select name="payment_status" onchange="this.form.submit()" class="badge badge-<?= $order['payment_status'] == 'paid' ? 'paid' : 'unpaid' ?>" style="border: none; cursor: pointer; font-family: inherit; font-size: 0.7rem; outline: none; padding: 3px 8px;">
                                        <option value="pending" <?= $order['payment_status'] == 'pending' ? 'selected' : '' ?>>BELUM LUNAS</option>
                                        <option value="paid" <?= $order['payment_status'] == 'paid' ? 'selected' : '' ?>>LUNAS</option>
                                    </select>
                                </form>
                            </td>
                            <!-- Order Status -->
                            <td>
                                <form action="<?= base_url('admin/update_order_status/' . $order['id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <select name="order_status" onchange="this.form.submit()" class="badge badge-<?= $order['order_status'] ?>" style="border: none; cursor: pointer; font-family: inherit; font-size: 0.7rem; outline: none; padding: 5px 8px;">
                                        <option value="pending" <?= $order['order_status'] == 'pending' ? 'selected' : '' ?>>Pending (Antre)</option>
                                        <option value="cooking" <?= $order['order_status'] == 'cooking' ? 'selected' : '' ?>>Dimasak</option>
                                        <option value="completed" <?= $order['order_status'] == 'completed' ? 'selected' : '' ?>>Selesai Disajikan</option>
                                        <option value="cancelled" <?= $order['order_status'] == 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                                    </select>
                                </form>
                            </td>
                            <!-- Actions -->
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a href="<?= base_url('customer/receipt/' . $order['id']) ?>" target="_blank" class="btn-light" style="padding: 6px 10px; font-size: 0.75rem;" title="Lihat/Cetak Struk">
                                        <i class="fa-solid fa-receipt"></i> Struk
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
