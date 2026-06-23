<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div style="margin-bottom: 25px;">
    <h2>
        <?php if (isset($menu)): ?>
            <i class="fa-solid fa-pen-to-square"></i> Ubah Data Menu: <?= esc($menu['name']) ?>
        <?php else: ?>
            <i class="fa-solid fa-circle-plus"></i> Tambah Menu Makanan / Minuman Baru
        <?php endif; ?>
    </h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">
        <?= isset($menu) ? 'Perbarui informasi menu, ganti gambar, deskripsi, status ketersediaan, atau harga.' : 'Masukkan nama, harga, deskripsi, gambar, dan kategori untuk membuat menu baru.' ?>
    </p>
</div>

<!-- Form CRUD -->
<div class="admin-card" style="max-width: 700px;">
    
    <form action="<?= isset($menu) ? base_url('admin/menu/update/' . $menu['id']) : base_url('admin/menu/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name" class="form-label"><i class="fa-solid fa-tag"></i> Nama Menu</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Nasi Goreng Spesial, Es Teh" value="<?= isset($menu) ? esc($menu['name']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="type" class="form-label"><i class="fa-solid fa-utensils"></i> Kategori Menu</label>
            <select name="type" id="type" class="form-control" required>
                <option value="makanan" <?= (isset($menu) && $menu['type'] == 'makanan') ? 'selected' : '' ?>>Makanan</option>
                <option value="minuman" <?= (isset($menu) && $menu['type'] == 'minuman') ? 'selected' : '' ?>>Minuman</option>
            </select>
        </div>

        <div class="form-group">
            <label for="price" class="form-label"><i class="fa-solid fa-money-bill-wave"></i> Harga Jual (Rp)</label>
            <input type="number" name="price" id="price" class="form-control" placeholder="Contoh: 12000" value="<?= isset($menu) ? esc($menu['price']) : '' ?>" min="1" required>
        </div>

        <div class="form-group">
            <label for="is_available" class="form-label"><i class="fa-solid fa-check"></i> Status Ketersediaan</label>
            <select name="is_available" id="is_available" class="form-control" required>
                <option value="1" <?= (isset($menu) && $menu['is_available'] == 1) ? 'selected' : '' ?>>Tersedia (Ready)</option>
                <option value="0" <?= (isset($menu) && $menu['is_available'] == 0) ? 'selected' : '' ?>>Habis (Sold Out)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description" class="form-label"><i class="fa-solid fa-align-left"></i> Deskripsi Menu</label>
            <textarea name="description" id="description" rows="4" class="form-control" placeholder="Tuliskan deskripsi menu yang menarik bagi pelanggan..."><?= isset($menu) ? esc($menu['description']) : '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="image" class="form-label"><i class="fa-regular fa-image"></i> Gambar Menu</label>
            
            <?php if (isset($menu) && !empty($menu['image'])): ?>
                <?php 
                    $imagePath = 'uploads/menus/' . $menu['image'];
                    if (file_exists(FCPATH . $imagePath)): 
                ?>
                    <div style="margin-bottom: 10px;">
                        <img src="<?= base_url($imagePath) ?>" alt="<?= esc($menu['name']) ?>" style="max-width: 150px; border-radius: 8px; border: 1px solid var(--border-color); display: block;">
                        <small style="color: var(--text-muted);">Gambar saat ini. Unggah gambar baru jika ingin menggantinya.</small>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <input type="file" name="image" id="image" class="form-control form-control-file" accept="image/*">
            <small style="color: var(--text-muted); display: block; margin-top: 5px;">Format gambar yang didukung: JPG, JPEG, PNG, WEBP. Maksimal ukuran 2MB.</small>
        </div>

        <div class="btn-group">
            <a href="<?= base_url('admin/menus') ?>" class="btn-light">
                <i class="fa-solid fa-xmark"></i> Batal
            </a>
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Simpan Data Menu
            </button>
        </div>

    </form>

</div>

<?= $this->endSection() ?>
