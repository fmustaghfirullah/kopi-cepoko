<?php
require_once 'backend/functions/frontend.php';

// Get page parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get products and categories
$products = $frontendManager->getProducts($page, $category_id);
$totalProducts = $frontendManager->getTotalProducts($category_id);
$categories = $frontendManager->getCategories();

// Calculate pagination
$pagination = calculatePagination($page, $totalProducts, DatabaseConfig::ITEMS_PER_PAGE);

// Get site settings
$whatsappNumber = getSiteSetting('whatsapp_number', '628123456789');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle('produk', 'Produk Kami - Kopi Cepoko'); ?></title>
    <meta name="description" content="Jelajahi koleksi lengkap produk kopi premium Desa Cepoko dengan berbagai varian dan tingkat roasting">
    <meta name="keywords" content="produk kopi cepoko, kopi premium, kopi bubuk, biji kopi, kopi arabika">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <img src="assets/images/logo.png" alt="Kopi Cepoko Logo" style="width: 40px; height: 40px; margin-right: 10px;">
                <span>Kopi Cepoko</span>
            </div>
            
            <nav class="navigation">
                <ul class="nav-menu">
                    <li><a href="index.php" class="nav-link">Beranda</a></li>
                    <li><a href="tentang.php" class="nav-link">Tentang Kami</a></li>
                    <li><a href="produk.php" class="nav-link active">Produk</a></li>
                    <li><a href="galeri.php" class="nav-link">Galeri</a></li>
                    <li><a href="kontak.php" class="nav-link">Kontak</a></li>
                </ul>
                
                <div class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Breadcrumb -->
    <section class="breadcrumb-section" style="background: #f8f9fa; padding: 1rem 0;">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background: none; margin: 0; padding: 0;">
                    <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Produk</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="hero" style="padding: 3rem 0;">
        <div class="container">
            <h1>Produk Kopi Cepoko</h1>
            <p>Pilihan lengkap kopi premium dengan cita rasa autentik Desa Cepoko</p>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section" style="padding: 2rem 0; background: #f8f9fa;">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="category-filters">
                        <h4>Filter Kategori:</h4>
                        <div class="filter-buttons">
                            <a href="produk.php" class="btn <?php echo !$category_id ? 'btn-primary' : 'btn-secondary'; ?>">
                                Semua Produk
                            </a>
                            <?php foreach ($categories as $category): ?>
                                <a href="produk.php?category=<?php echo $category['id']; ?>" 
                                   class="btn <?php echo $category_id == $category['id'] ? 'btn-primary' : 'btn-secondary'; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="search-box">
                        <h4>Cari Produk:</h4>
                        <form method="GET" action="produk.php" class="search-form">
                            <?php if ($category_id): ?>
                                <input type="hidden" name="category" value="<?php echo $category_id; ?>">
                            <?php endif; ?>
                            <div class="search-input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Nama produk..." 
                                       value="<?php echo htmlspecialchars($search); ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="section">
        <div class="container">
            <!-- Results Info -->
            <div class="results-info" style="margin-bottom: 2rem;">
                <p>Menampilkan <?php echo count($products); ?> dari <?php echo $totalProducts; ?> produk
                <?php if ($category_id): ?>
                    dalam kategori <strong><?php 
                        $selectedCategory = array_filter($categories, function($cat) use ($category_id) {
                            return $cat['id'] == $category_id;
                        });
                        echo htmlspecialchars(reset($selectedCategory)['name']);
                    ?></strong>
                <?php endif; ?>
                <?php if ($search): ?>
                    untuk pencarian <strong>"<?php echo htmlspecialchars($search); ?>"</strong>
                <?php endif; ?>
                </p>
            </div>

            <!-- Products Grid -->
            <?php if (empty($products)): ?>
                <div class="no-products text-center" style="padding: 3rem 0;">
                    <i class="fas fa-coffee" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <h3>Tidak ada produk yang ditemukan</h3>
                    <p>Coba ubah filter atau kata kunci pencarian Anda</p>
                    <a href="produk.php" class="btn btn-primary">Lihat Semua Produk</a>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="card product-card">
                            <div class="product-image">
                                <img src="<?php echo $product['image'] ? 'backend/uploads/products/' . $product['image'] : 'assets/images/placeholder-product.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="card-img">
                                <?php if ($product['is_featured']): ?>
                                    <div class="featured-badge">
                                        <i class="fas fa-star"></i> Unggulan
                                    </div>
                                <?php endif; ?>
                                <?php if ($product['stock_quantity'] <= 0): ?>
                                    <div class="out-of-stock-badge">
                                        Stok Habis
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body">
                                <div class="product-category">
                                    <span class="category-badge"><?php echo htmlspecialchars($product['category_name']); ?></span>
                                </div>
                                
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($product['short_description']); ?></p>
                                
                                <div class="product-details">
                                    <div class="product-weight">
                                        <i class="fas fa-weight-hanging"></i> <?php echo $product['weight']; ?>g
                                    </div>
                                    <div class="product-stock">
                                        <i class="fas fa-box"></i> Stok: <?php echo $product['stock_quantity']; ?>
                                    </div>
                                </div>
                                
                                <div class="card-price"><?php echo formatCurrency($product['price']); ?></div>
                                
                                <div class="product-actions">
                                    <?php if ($product['stock_quantity'] > 0): ?>
                                        <button class="btn btn-primary btn-order" 
                                                data-product-id="<?php echo $product['id']; ?>"
                                                data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                                                data-product-price="<?php echo $product['price']; ?>">
                                            <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-times"></i> Stok Habis
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-secondary btn-details" 
                                            data-product-id="<?php echo $product['id']; ?>">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <nav class="pagination-nav">
                        <ul class="pagination">
                            <?php if ($pagination['has_previous']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="produk.php?page=<?php echo $pagination['previous_page']; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                        <i class="fas fa-chevron-left"></i> Sebelumnya
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php 
                            $start = max(1, $pagination['current_page'] - 2);
                            $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            for ($i = $start; $i <= $end; $i++): 
                            ?>
                                <li class="page-item <?php echo $i == $pagination['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="produk.php?page=<?php echo $i; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="produk.php?page=<?php echo $pagination['next_page']; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                        Selanjutnya <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="section bg-light">
        <div class="container">
            <h2 class="section-title">Mengapa Memilih Kopi Cepoko?</h2>
            <div class="row">
                <div class="col-3">
                    <div class="feature-box text-center">
                        <i class="fas fa-certificate" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h4>Kualitas Premium</h4>
                        <p>Hanya biji kopi pilihan terbaik yang telah melalui proses seleksi ketat</p>
                    </div>
                </div>
                <div class="col-3">
                    <div class="feature-box text-center">
                        <i class="fas fa-leaf" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h4>100% Natural</h4>
                        <p>Tanpa bahan kimia berbahaya, diproses secara tradisional dan alami</p>
                    </div>
                </div>
                <div class="col-3">
                    <div class="feature-box text-center">
                        <i class="fas fa-shipping-fast" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h4>Pengiriman Cepat</h4>
                        <p>Pengiriman ke seluruh Indonesia dengan kemasan yang aman dan terjamin</p>
                    </div>
                </div>
                <div class="col-3">
                    <div class="feature-box text-center">
                        <i class="fas fa-handshake" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h4>Pelayanan Terbaik</h4>
                        <p>Customer service yang responsif dan siap membantu 24/7</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section bg-primary" style="color: white;">
        <div class="container text-center">
            <h2>Pesan Kopi Favorit Anda Sekarang</h2>
            <p>Hubungi kami melalui WhatsApp untuk pemesanan yang lebih mudah dan cepat</p>
            <a href="https://wa.me/<?php echo $whatsappNumber; ?>?text=Halo%20Kopi%20Cepoko,%20saya%20tertarik%20untuk%20memesan%20produk%20Anda" 
               class="btn btn-whatsapp" target="_blank">
                <i class="fab fa-whatsapp"></i> Chat WhatsApp
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Kopi Cepoko</h4>
                    <p><?php echo getSiteSetting('site_description', 'Kopi premium dari Desa Cepoko dengan cita rasa autentik dan tradisional'); ?></p>
                    <div class="social-links">
                        <a href="<?php echo getSiteSetting('instagram_url', '#'); ?>" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?php echo getSiteSetting('facebook_url', '#'); ?>" target="_blank">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://wa.me/<?php echo $whatsappNumber; ?>" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Menu</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="tentang.php">Tentang Kami</a></li>
                        <li><a href="produk.php">Produk</a></li>
                        <li><a href="galeri.php">Galeri</a></li>
                        <li><a href="kontak.php">Kontak</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Kontak</h4>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo getSiteSetting('contact_address', 'Desa Cepoko, Kecamatan Cepoko, Kabupaten Boyolali, Jawa Tengah'); ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo getSiteSetting('contact_phone', '08123456789'); ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo getSiteSetting('contact_email', 'info@kopicepoko.com'); ?></p>
                </div>
                
                <div class="footer-section">
                    <h4>Jam Operasional</h4>
                    <p><?php echo getSiteSetting('operating_hours', 'Senin - Sabtu: 08:00 - 17:00 WIB'); ?></p>
                    <h5>Info Pengiriman</h5>
                    <p><?php echo getSiteSetting('shipping_info', 'Gratis ongkir untuk pembelian minimal Rp 100.000'); ?></p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 Kopi Cepoko. Semua hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
    
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/<?php echo $whatsappNumber; ?>?text=Halo%20Kopi%20Cepoko,%20saya%20tertarik%20dengan%20produk%20Anda" 
       class="whatsapp-float" target="_blank" title="Chat via WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    
    <style>
    .whatsapp-float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 40px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 2px 2px 3px #999;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .whatsapp-float:hover {
        background-color: #128c7e;
        color: #FFF;
        transform: scale(1.1);
    }
    
    .social-links {
        margin-top: 15px;
    }
    
    .social-links a {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.1);
        color: white;
        text-align: center;
        line-height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        transition: all 0.3s ease;
    }
    
    .social-links a:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
    }
    
    .breadcrumb {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
        padding: 0 0.5rem;
        color: #6c757d;
    }
    
    .breadcrumb-item a {
        color: var(--primary-color);
    }
    
    .breadcrumb-item.active {
        color: #6c757d;
    }
    
    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .search-input-group {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .search-input-group .form-control {
        flex: 1;
    }
    
    .product-card {
        position: relative;
        transition: all 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .product-image {
        position: relative;
        overflow: hidden;
    }
    
    .featured-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: var(--warning-color);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .out-of-stock-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--danger-color);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .category-badge {
        background: var(--accent-color);
        color: white;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .product-details {
        display: flex;
        justify-content: space-between;
        margin: 0.5rem 0;
        font-size: 0.875rem;
        color: var(--text-light);
    }
    
    .product-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .product-actions .btn {
        flex: 1;
        font-size: 0.875rem;
        padding: 8px 12px;
    }
    
    .pagination {
        justify-content: center;
        margin: 2rem 0;
    }
    
    .pagination .page-item {
        margin: 0 2px;
    }
    
    .pagination .page-link {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .pagination .page-item.active .page-link {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .pagination .page-link:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .feature-box {
        padding: 2rem 1rem;
    }
    
    @media (max-width: 768px) {
        .whatsapp-float {
            width: 50px;
            height: 50px;
            bottom: 20px;
            right: 20px;
            font-size: 24px;
        }
        
        .row {
            flex-direction: column;
        }
        
        .col-3, .col-6 {
            max-width: 100%;
            margin-bottom: 1rem;
        }
        
        .filter-buttons {
            justify-content: center;
        }
        
        .search-input-group {
            flex-direction: column;
        }
        
        .product-actions {
            flex-direction: column;
        }
        
        .product-details {
            flex-direction: column;
            gap: 0.25rem;
        }
    }
    </style>
</body>
</html>