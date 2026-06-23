<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - Burjo Modern</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/3448/3448609.png" type="image/x-icon">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

    <!-- Header Navbar -->
    <header class="header" style="background-color: #1b4332; border-bottom: 3px solid #ffb703;">
        <div class="container header-container">
            <a href="<?= base_url('admin') ?>" class="logo-link">
                <i class="fa-solid fa-user-shield logo-icon" style="color: #ffb703;"></i>
                <div class="logo-text">ADMIN<span>BURJO</span></div>
            </a>
            <nav class="nav-menu">
                <a href="<?= base_url() ?>" class="nav-link" target="_blank">
                    <i class="fa-solid fa-globe"></i> Lihat Toko
                </a>
                <a href="<?= base_url('admin/logout') ?>" class="nav-admin-btn" style="background-color: var(--danger); color: white;">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </a>
            </nav>
        </div>
    </header>

    <!-- Admin Panel Layout Grid -->
    <div class="admin-layout">
        
        <!-- Left Sidebar -->
        <aside class="admin-sidebar">
            <nav class="sidebar-menu">
                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px; padding: 0 16px;">Main Menu</div>
                
                <a href="<?= base_url('admin/orders') ?>" class="sidebar-link <?= (current_url() == base_url('admin/orders') || current_url() == base_url('admin')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-list-check"></i> Kelola Pesanan
                </a>
                
                <a href="<?= base_url('admin/menus') ?>" class="sidebar-link <?= (current_url() == base_url('admin/menus') || strpos(current_url(), 'admin/menu/') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-burger"></i> Kelola Menu
                </a>
                
                <div class="receipt-divider" style="margin: 15px 0;"></div>
                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px; padding: 0 16px;">Sistem</div>
                
                <a href="<?= base_url() ?>" class="sidebar-link">
                    <i class="fa-solid fa-arrow-left-long"></i> Halaman Depan
                </a>
            </nav>
        </aside>

        <!-- Right Content Body -->
        <main class="admin-content">
            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div style="background-color: #d3f9d8; color: #2b8a3e; border: 1px solid #b2f2bb; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                    <i class="fa-solid fa-circle-check"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div style="background-color: #ffe3e3; color: #c92a2a; border: 1px solid #ffa8a8; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>

    </div>

    <!-- Scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
