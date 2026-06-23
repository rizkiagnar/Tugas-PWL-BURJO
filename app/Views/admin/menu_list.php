<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h2><i class="fa-solid fa-burger"></i> Kelola Data Makanan & Minuman</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Kelola menu Burjo Modern (tambah, perbarui harga/gambar, deskripsi, atau hapus item menu).</p>
    </div>
    <a href="<?= base_url('admin/menu/create') ?>" class="btn-primary" style="display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-circle-plus"></i> Tambah Menu Baru
    </a>
</div>

<!-- Menus Table -->
<div class="admin-card">
    <div class="orders-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Gambar</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>Ketersediaan</th>
                    <th>Deskripsi</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($menus)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <i class="fa-solid fa-circle-exclamation" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                            Belum ada data menu. Silakan tambahkan menu baru.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($menus as $item): ?>
                        <tr>
                            <!-- Image -->
                            <td>
                                <?php 
                                    $imagePath = 'uploads/menus/' . $item['image'];
                                    if (!empty($item['image']) && file_exists(FCPATH . $imagePath)): 
                                ?>
                                    <img src="<?= base_url($imagePath) ?>" alt="<?= esc($item['name']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; border-radius: 8px; background-color: #f1f3f5; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; border: 1px solid var(--border-color);" title="Gambar default">
                                        <?= $item['type'] == 'makanan' ? '🍜' : '🥤' ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <!-- Name -->
                            <td>
                                <strong><?= esc($item['name']) ?></strong>
                            </td>
                            <!-- Type -->
                            <td>
                                <span class="badge <?= $item['type'] == 'makanan' ? 'badge-cooking' : 'badge-qris' ?>">
                                    <?= $item['type'] == 'makanan' ? 'MAKANAN' : 'MINUMAN' ?>
                                </span>
                            </td>
                            <!-- Price -->
                            <td>
                                <strong style="font-family: var(--font-heading); color: var(--primary);">Rp <?= number_format($item['price'], 0, ',', '.') ?></strong>
                            </td>
                            <!-- Availability status -->
                            <td>
                                <span class="badge badge-<?= $item['is_available'] ? 'completed' : 'cancelled' ?>">
                                    <?= $item['is_available'] ? 'Tersedia' : 'Habis' ?>
                                </span>
                            </td>
                            <!-- Description -->
                            <td>
                                <div style="font-size: 0.8rem; color: var(--text-muted); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($item['description']) ?>">
                                    <?= esc($item['description'] ?? '-') ?>
                                </div>
                            </td>
                            <!-- Actions -->
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="<?= base_url('admin/menu/edit/' . $item['id']) ?>" class="btn-light" style="padding: 6px 12px; font-size: 0.8rem; color: var(--info); border-color: rgba(0,119,182,0.2); background-color: rgba(0,119,182,0.02);" title="Ubah data menu">
                                        <i class="fa-solid fa-pen-to-square"></i> Ubah
                                    </a>
                                    <a href="<?= base_url('admin/menu/delete/' . $item['id']) ?>" class="btn-danger" style="padding: 6px 12px; font-size: 0.8rem;" onclick="return confirm('Apakah Anda yakin ingin menghapus menu <?= esc($item['name']) ?>?')" title="Hapus menu">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
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
