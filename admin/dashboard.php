<?php
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
require_once 'partials/header.php';

require_once '../backend/functions/admin.php';
$adminManager = new AdminManager();

// Get dashboard statistics
$stats = $adminManager->getDashboardStats();
?>

<!-- Statistics Cards -->
<div class="dashboard-cards">
    <div class="dashboard-card products">
        <i class="fas fa-coffee"></i>
        <h3><?php echo $stats['total_products']; ?></h3>
        <p>Total Produk</p>
    </div>
    
    <div class="dashboard-card orders">
        <i class="fas fa-shopping-cart"></i>
        <h3><?php echo $stats['total_orders']; ?></h3>
        <p>Total Pesanan</p>
    </div>
    
    <div class="dashboard-card gallery">
        <i class="fas fa-images"></i>
        <h3><?php echo $stats['total_gallery']; ?></h3>
        <p>Total Galeri</p>
    </div>
    
    <div class="dashboard-card messages">
        <i class="fas fa-envelope"></i>
        <h3><?php echo $stats['unread_messages']; ?></h3>
        <p>Pesan Belum Dibaca</p>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>