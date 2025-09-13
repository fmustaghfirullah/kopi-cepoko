<?php
require_once '../../backend/config/database.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin Kopi Cepoko</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Include sidebar here -->
        <nav class="admin-sidebar">
            <div class="sidebar-header">
                <h3>Kopi Cepoko</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="../dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="../content/"><i class="fas fa-edit"></i> <span>Kelola Konten</span></a></li>
                <li><a href="./" class="active"><i class="fas fa-coffee"></i> <span>Kelola Produk</span></a></li>
                <li><a href="../gallery/"><i class="fas fa-images"></i> <span>Kelola Galeri</span></a></li>
                <li><a href="../orders/"><i class="fas fa-shopping-cart"></i> <span>Kelola Pesanan</span></a></li>
                <li><a href="../settings/"><i class="fas fa-cog"></i> <span>Pengaturan</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <header class="admin-header">
                <h1>Kelola Produk</h1>
                <div class="user-menu">
                    <a href="add.php" class="btn-admin btn-primary-admin">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                </div>
            </header>
            
            <div class="admin-content">
                <div class="admin-table">
                    <div class="table-header">
                        <h3>Daftar Produk</h3>
                    </div>
                    
                    <div style="padding: 2rem; text-align: center;">
                        <i class="fas fa-coffee" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
                        <h4>Halaman dalam pengembangan</h4>
                        <p>Fitur kelola produk sedang dalam tahap pengembangan.</p>
                        <p>Silakan implementasikan CRUD operations untuk produk di halaman ini.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../../assets/js/admin.js"></script>
</body>
</html>