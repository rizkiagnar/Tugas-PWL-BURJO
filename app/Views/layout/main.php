<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Warung Burjo Modern' ?> - Pemesanan Praktis</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/3448/3448609.png" type="image/x-icon">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

    <!-- Header Navbar -->
    <header class="header">
        <div class="container header-container">
            <a href="<?= base_url() ?>" class="logo-link">
                <i class="fa-solid fa-store logo-icon"></i>
                <div class="logo-text">BURJO<span>MODERN</span></div>
            </a>
            <nav class="nav-menu">
                <a href="<?= base_url() ?>" class="nav-link <?= (current_url() == base_url() || current_url() == base_url('index.php')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-utensils"></i> Menu
                </a>
                <a href="<?= base_url('admin') ?>" class="nav-admin-btn">
                    <i class="fa-solid fa-user-gear"></i> Admin Panel
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content Yield -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer style="background-color: #1b4332; color: #b7e4c7; text-align: center; padding: 20px 0; font-size: 0.85rem; border-top: 4px solid #ffb703; position: relative; bottom: 0; width: 100%; margin-top: 50px;">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Burjo Modern. Pemrograman Web Lanjut - A11.2024.15786</p>
            <p style="margin-top: 5px; opacity: 0.7; font-weight: 300;">Dibuat dengan rasa cinta, dan sangat enak </p>
        </div>
    </footer>

    <!-- Global Cart Script -->
    <script src="<?= base_url('js/cart.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
