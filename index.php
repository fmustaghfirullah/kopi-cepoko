<?php
require_once 'backend/functions/frontend.php';

// Get page content
$heroTitle = getSiteContent('home', 'hero', 'title', 'Kopi Cepoko Asli Desa Cepoko');
$heroSubtitle = getSiteContent('home', 'hero', 'subtitle', 'Cita Rasa Autentik dari Tanah Jawa');
$heroDescription = getSiteContent('home', 'hero', 'description', 'Nikmati kelezatan kopi premium dari perkebunan tradisional Desa Cepoko dengan cita rasa yang telah diwariskan turun temurun.');

// Get featured products
$featuredProducts = $frontendManager->getFeaturedProducts(6);

// Get featured gallery
$featuredGallery = $frontendManager->getFeaturedGallery(6);

// Get site settings
$whatsappNumber = getSiteSetting('whatsapp_number', '628123456789');
$contactPhone = getSiteSetting('contact_phone', '08123456789');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle('home', 'Kopi Cepoko - UMKM Desa Cepoko'); ?></title>
    <meta name="description" content="<?php echo getSiteSetting('site_description', 'Kopi premium dari Desa Cepoko dengan cita rasa autentik dan tradisional'); ?>">
    <meta name="keywords" content="kopi cepoko, kopi desa, kopi premium, kopi jawa, UMKM kopi">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Kopi Cepoko - UMKM Desa Cepoko">
    <meta property="og:description" content="Kopi premium dari Desa Cepoko dengan cita rasa autentik dan tradisional">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo DatabaseConfig::SITE_URL; ?>">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
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
                    <li><a href="index.php" class="nav-link active">Beranda</a></li>
                    <li><a href="tentang.php" class="nav-link">Tentang Kami</a></li>
                    <li><a href="produk.php" class="nav-link">Produk</a></li>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1><?php echo htmlspecialchars($heroTitle); ?></h1>
            <p><?php echo htmlspecialchars($heroSubtitle); ?></p>
            <p><?php echo htmlspecialchars($heroDescription); ?></p>
            <div class="hero-buttons">
                <a href="produk.php" class="btn btn-primary">Lihat Produk</a>
                <a href="tentang.php" class="btn btn-secondary">Tentang Kami</a>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Produk Unggulan</h2>
            <p class="section-subtitle">Pilihan terbaik kopi premium dari Desa Cepoko</p>
            
            <div class="product-grid">
                <?php if (empty($featuredProducts)): ?>
                    <div class="col-12">
                        <p class="text-center">Belum ada produk unggulan yang tersedia.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($featuredProducts as $product): ?>
                        <div class="card">
                            <img src="<?php echo $product['image'] ? 'backend/uploads/products/' . $product['image'] : 'assets/images/placeholder-product.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 class="card-img">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($product['short_description']); ?></p>
                                <div class="card-price"><?php echo formatCurrency($product['price']); ?></div>
                                <button class="btn btn-primary btn-order" 
                                        data-product-id="<?php echo $product['id']; ?>"
                                        data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                                        data-product-price="<?php echo $product['price']; ?>">
                                    <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="produk.php" class="btn btn-secondary">Lihat Semua Produk</a>
            </div>
        </div>
    </section>

    <!-- About Preview Section -->
    <section class="section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h2><?php echo getSiteContent('home', 'about', 'title', 'Tentang Kopi Cepoko'); ?></h2>
                    <p><?php echo getSiteContent('home', 'about', 'content', 'Kopi Cepoko adalah warisan budaya dari Desa Cepoko yang telah dibudidayakan secara tradisional selama puluhan tahun. Dengan tanah vulkanis yang subur dan iklim tropis yang ideal, kopi kami memiliki karakteristik rasa yang unik dan autentik.'); ?></p>
                    <a href="tentang.php" class="btn btn-primary">Selengkapnya</a>
                </div>
                <div class="col-6">
                    <img src="assets/images/about-preview.jpg" alt="Tentang Kopi Cepoko" style="width: 100%; border-radius: 8px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Gallery Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Galeri Kami</h2>
            <p class="section-subtitle">Lihat proses pembuatan kopi dari kebun hingga cangkir</p>
            
            <div class="gallery-grid">
                <?php if (empty($featuredGallery)): ?>
                    <div class="col-12">
                        <p class="text-center">Belum ada galeri yang tersedia.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($featuredGallery as $gallery): ?>
                        <div class="gallery-item">
                            <img src="<?php echo $gallery['image'] ? 'backend/uploads/gallery/' . $gallery['image'] : 'assets/images/placeholder-gallery.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($gallery['title']); ?>">
                            <div class="gallery-overlay">
                                <h5><?php echo htmlspecialchars($gallery['title']); ?></h5>
                                <p><?php echo htmlspecialchars($gallery['description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="galeri.php" class="btn btn-secondary">Lihat Semua Galeri</a>
            </div>
        </div>
    </section>

    <!-- Quick Contact Section -->
    <section class="section bg-primary" style="color: white;">
        <div class="container text-center">
            <h2>Hubungi Kami Sekarang</h2>
            <p>Pesan kopi premium Desa Cepoko melalui WhatsApp</p>
            <div class="mt-4">
                <a href="https://wa.me/<?php echo $whatsappNumber; ?>?text=Halo%20Kopi%20Cepoko,%20saya%20tertarik%20dengan%20produk%20Anda" 
                   class="btn btn-whatsapp" target="_blank">
                    <i class="fab fa-whatsapp"></i> Chat WhatsApp
                </a>
                <a href="tel:<?php echo $contactPhone; ?>" class="btn btn-secondary">
                    <i class="fas fa-phone"></i> Telepon
                </a>
            </div>
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
                    <p><i class="fas fa-phone"></i> <?php echo $contactPhone; ?></p>
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
    
    @media (max-width: 768px) {
        .whatsapp-float {
            width: 50px;
            height: 50px;
            bottom: 20px;
            right: 20px;
            font-size: 24px;
        }
        
        .hero h1 {
            font-size: 2rem;
        }
        
        .hero p {
            font-size: 1rem;
        }
        
        .hero-buttons {
            flex-direction: column;
            gap: 1rem;
        }
        
        .hero-buttons .btn {
            width: 100%;
        }
        
        .row {
            flex-direction: column;
        }
        
        .col-6 {
            max-width: 100%;
        }
    }
    </style>
</body>
</html>