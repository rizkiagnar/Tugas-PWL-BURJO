<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Burjo Modern</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/3448/3448609.png" type="image/x-icon">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body style="display: flex; align-items: center; min-height: 100vh; background-color: var(--primary);">

    <div class="container">
        
        <div class="checkout-card admin-login-wrapper" style="margin: 0 auto; padding: 40px 30px;">
            
            <div class="checkout-header">
                <div class="receipt-brand" style="font-size: 1.8rem; margin-bottom: 5px;">ADMIN<span>BURJO</span></div>
                <p class="checkout-subtitle">Silakan masuk untuk mengelola menu dan pesanan</p>
            </div>

            <!-- Error Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div style="background-color: #ffe3e3; color: #c92a2a; border: 1px solid #ffa8a8; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600; text-align: center;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/login_submit') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="username" class="form-label"><i class="fa-solid fa-user"></i> Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username admin" required autofocus autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label"><i class="fa-solid fa-key"></i> Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn-checkout" style="margin-top: 10px;">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk Sekarang
                </button>
            </form>

            <div style="text-align: center; margin-top: 25px;">
                <a href="<?= base_url() ?>" style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Menu Utama
                </a>
            </div>

        </div>

    </div>

</body>
</html>
