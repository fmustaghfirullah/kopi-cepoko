<?php
require_once __DIR__ . '/../../config/database.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin - Kopi Cepoko'; ?></title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav class="admin-sidebar">
            <div class="sidebar-header">
                <h3>Kopi Cepoko</h3>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/dashboard.php" class="<?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/products/">
                        <i class="fas fa-coffee"></i>
                        <span>Kelola Produk</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/gallery/">
                        <i class="fas fa-images"></i>
                        <span>Kelola Galeri</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/orders/">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Kelola Pesanan</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/settings/">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="toggle-sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
                </div>
                
                <div class="user-menu">
                    <span>Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                    <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn-admin btn-secondary-admin btn-sm">
                        <i class="fas fa-external-link-alt"></i> Lihat Website
                    </a>
                </div>
            </header>
            
            <!-- Content -->
            <div class="admin-content">