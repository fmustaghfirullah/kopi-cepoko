<?php
require_once '../backend/config/database.php';
requireLogin();

require_once '../backend/functions/admin.php';
$adminManager = new AdminManager();

// Get dashboard statistics
$stats = $adminManager->getDashboardStats();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Kopi Cepoko</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav class="admin-sidebar">
            <div class="sidebar-header">
                <h3>Kopi Cepoko</h3>
                <p>Admin Panel</p>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="dashboard.php" class="active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="content/">
                        <i class="fas fa-edit"></i>
                        <span>Kelola Konten</span>
                    </a>
                </li>
                <li>
                    <a href="products/">
                        <i class="fas fa-coffee"></i>
                        <span>Kelola Produk</span>
                    </a>
                </li>
                <li>
                    <a href="gallery/">
                        <i class="fas fa-images"></i>
                        <span>Kelola Galeri</span>
                    </a>
                </li>
                <li>
                    <a href="orders/">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Kelola Pesanan</span>
                    </a>
                </li>
                <li>
                    <a href="settings/">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
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
                    <h1>Dashboard</h1>
                </div>
                
                <div class="user-menu">
                    <span>Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                    <a href="../index.php" target="_blank" class="btn-admin btn-secondary-admin btn-sm">
                        <i class="fas fa-external-link-alt"></i> Lihat Website
                    </a>
                </div>
            </header>
            
            <!-- Content -->
            <div class="admin-content">
                <!-- Statistics Cards -->
                <div class="dashboard-cards">
                    <div class="dashboard-card products">
                        <i class="fas fa-coffee"></i>
                        <h3><?php echo $stats['total_products']; ?></h3>
                        <p>Total Produk</p>
                        <a href="products/" class="card-link">Kelola Produk</a>
                    </div>
                    
                    <div class="dashboard-card orders">
                        <i class="fas fa-shopping-cart"></i>
                        <h3><?php echo $stats['total_orders']; ?></h3>
                        <p>Total Pesanan</p>
                        <a href="orders/" class="card-link">Kelola Pesanan</a>
                    </div>
                    
                    <div class="dashboard-card gallery">
                        <i class="fas fa-images"></i>
                        <h3><?php echo $stats['total_gallery']; ?></h3>
                        <p>Total Galeri</p>
                        <a href="gallery/" class="card-link">Kelola Galeri</a>
                    </div>
                    
                    <div class="dashboard-card messages">
                        <i class="fas fa-envelope"></i>
                        <h3><?php echo $stats['unread_messages']; ?></h3>
                        <p>Pesan Belum Dibaca</p>
                        <a href="settings/messages.php" class="card-link">Lihat Pesan</a>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="admin-table">
                    <div class="table-header">
                        <h3>Aksi Cepat</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="quick-actions">
                            <a href="products/add.php" class="btn-admin btn-primary-admin">
                                <i class="fas fa-plus"></i> Tambah Produk
                            </a>
                            <a href="gallery/add.php" class="btn-admin btn-success-admin">
                                <i class="fas fa-image"></i> Tambah Galeri
                            </a>
                            <a href="content/" class="btn-admin btn-warning-admin">
                                <i class="fas fa-edit"></i> Edit Konten
                            </a>
                            <a href="settings/" class="btn-admin btn-secondary-admin">
                                <i class="fas fa-cog"></i> Pengaturan
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin: 2rem 0;">
                    <!-- Recent Orders -->
                    <div class="col-6">
                        <div class="admin-table">
                            <div class="table-header">
                                <h3>Pesanan Terbaru</h3>
                                <a href="orders/" class="btn-admin btn-primary-admin btn-sm">Lihat Semua</a>
                            </div>
                            
                            <?php if (empty($stats['recent_orders'])): ?>
                                <div style="padding: 2rem; text-align: center; color: #666;">
                                    <i class="fas fa-shopping-cart" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                                    <p>Belum ada pesanan</p>
                                </div>
                            <?php else: ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>No. Pesanan</th>
                                            <th>Pelanggan</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stats['recent_orders'] as $order): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                                <td><?php echo formatCurrency($order['total_amount']); ?></td>
                                                <td>
                                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                                        <?php echo ucfirst($order['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo formatDate($order['created_at']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Website Stats -->
                    <div class="col-6">
                        <div class="admin-table">
                            <div class="table-header">
                                <h3>Statistik Website</h3>
                            </div>
                            <div style="padding: 1.5rem;">
                                <canvas id="statsChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- System Information -->
                <div class="admin-table">
                    <div class="table-header">
                        <h3>Informasi Sistem</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="system-info">
                            <div class="info-grid">
                                <div class="info-item">
                                    <i class="fas fa-server"></i>
                                    <div>
                                        <h5>Server</h5>
                                        <p><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <i class="fas fa-code"></i>
                                    <div>
                                        <h5>PHP Version</h5>
                                        <p><?php echo PHP_VERSION; ?></p>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <i class="fas fa-database"></i>
                                    <div>
                                        <h5>Database</h5>
                                        <p>MySQL (Connected)</p>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <h5>Last Login</h5>
                                        <p><?php echo date('d M Y H:i'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tips -->
                <div class="admin-table">
                    <div class="table-header">
                        <h3>Tips & Panduan</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="tips-grid">
                            <div class="tip-item">
                                <i class="fas fa-lightbulb"></i>
                                <h5>Kelola Produk</h5>
                                <p>Perbarui deskripsi dan foto produk secara berkala untuk meningkatkan penjualan.</p>
                            </div>
                            
                            <div class="tip-item">
                                <i class="fas fa-camera"></i>
                                <h5>Update Galeri</h5>
                                <p>Tambahkan foto-foto terbaru dari proses pembuatan kopi untuk menarik pelanggan.</p>
                            </div>
                            
                            <div class="tip-item">
                                <i class="fas fa-chart-line"></i>
                                <h5>Monitor Pesanan</h5>
                                <p>Pantau pesanan masuk secara rutin dan respon pelanggan dengan cepat.</p>
                            </div>
                            
                            <div class="tip-item">
                                <i class="fas fa-mobile-alt"></i>
                                <h5>Respon WhatsApp</h5>
                                <p>Pastikan nomor WhatsApp selalu aktif untuk melayani pelanggan 24/7.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="../assets/js/admin.js"></script>
    
    <style>
    .quick-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .card-link {
        display: block;
        text-align: center;
        margin-top: 1rem;
        color: var(--admin-accent);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .card-link:hover {
        color: var(--admin-primary);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: var(--admin-radius);
    }
    
    .info-item i {
        font-size: 1.5rem;
        color: var(--admin-accent);
        width: 40px;
        text-align: center;
    }
    
    .info-item h5 {
        margin: 0 0 0.25rem 0;
        color: var(--admin-dark);
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .info-item p {
        margin: 0;
        color: #666;
        font-size: 0.875rem;
    }
    
    .tips-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .tip-item {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: var(--admin-radius);
        border-left: 4px solid var(--admin-accent);
    }
    
    .tip-item i {
        font-size: 1.5rem;
        color: var(--admin-accent);
        margin-bottom: 0.75rem;
    }
    
    .tip-item h5 {
        margin: 0 0 0.5rem 0;
        color: var(--admin-dark);
    }
    
    .tip-item p {
        margin: 0;
        color: #666;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .row {
        display: flex;
        gap: 2rem;
    }
    
    .col-6 {
        flex: 1;
    }
    
    @media (max-width: 768px) {
        .quick-actions {
            flex-direction: column;
        }
        
        .row {
            flex-direction: column;
            gap: 1rem;
        }
        
        .info-grid,
        .tips-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
    
    <script>
    // Initialize charts
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('statsChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Produk Aktif', 'Pesanan Pending', 'Galeri', 'Pesan'],
                datasets: [{
                    data: [
                        <?php echo $stats['total_products']; ?>,
                        <?php echo $stats['pending_orders']; ?>,
                        <?php echo $stats['total_gallery']; ?>,
                        <?php echo $stats['unread_messages']; ?>
                    ],
                    backgroundColor: [
                        '#3498DB',
                        '#27AE60', 
                        '#F39C12',
                        '#E74C3C'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Auto-refresh dashboard every 5 minutes
        setInterval(function() {
            location.reload();
        }, 300000);
    });
    </script>
</body>
</html>