<?php
require_once 'backend/functions/frontend.php';

// Get page parameters
$category = isset($_GET['category']) ? trim($_GET['category']) : null;

// Get gallery items
$galleryItems = $frontendManager->getGalleryItems($category);

// Gallery categories
$galleryCategories = [
    'plantation' => 'Perkebunan',
    'process' => 'Proses Pengolahan',
    'product' => 'Produk',
    'other' => 'Lainnya'
];

// Get site settings
$whatsappNumber = getSiteSetting('whatsapp_number', '628123456789');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle('galeri', 'Galeri - Kopi Cepoko'); ?></title>
    <meta name="description" content="Lihat koleksi foto perkebunan, proses pengolahan, dan produk kopi premium Desa Cepoko">
    <meta name="keywords" content="galeri kopi cepoko, foto perkebunan kopi, proses kopi, foto produk kopi">
    
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
                    <li><a href="produk.php" class="nav-link">Produk</a></li>
                    <li><a href="galeri.php" class="nav-link active">Galeri</a></li>
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
                    <li class="breadcrumb-item active" aria-current="page">Galeri</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="hero" style="padding: 3rem 0;">
        <div class="container">
            <h1>Galeri Kopi Cepoko</h1>
            <p>Saksikan perjalanan kopi dari kebun hingga cangkir Anda</p>
        </div>
    </section>

    <!-- Category Filters -->
    <section class="filters-section" style="padding: 2rem 0; background: #f8f9fa;">
        <div class="container">
            <div class="text-center">
                <h4>Filter Kategori:</h4>
                <div class="category-filters">
                    <a href="galeri.php" class="btn <?php echo !$category ? 'btn-primary' : 'btn-secondary'; ?>">
                        Semua Galeri
                    </a>
                    <?php foreach ($galleryCategories as $catKey => $catName): ?>
                        <a href="galeri.php?category=<?php echo $catKey; ?>" 
                           class="btn <?php echo $category == $catKey ? 'btn-primary' : 'btn-secondary'; ?>">
                            <?php echo $catName; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="section">
        <div class="container">
            <!-- Results Info -->
            <div class="results-info text-center" style="margin-bottom: 2rem;">
                <p>Menampilkan <?php echo count($galleryItems); ?> foto
                <?php if ($category): ?>
                    dalam kategori <strong><?php echo $galleryCategories[$category]; ?></strong>
                <?php endif; ?>
                </p>
            </div>

            <!-- Gallery Grid -->
            <?php if (empty($galleryItems)): ?>
                <div class="no-gallery text-center" style="padding: 3rem 0;">
                    <i class="fas fa-images" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <h3>Belum ada galeri yang tersedia</h3>
                    <p>Kembali lagi nanti untuk melihat foto-foto terbaru kami</p>
                    <a href="galeri.php" class="btn btn-primary">Lihat Semua Galeri</a>
                </div>
            <?php else: ?>
                <div class="gallery-grid masonry">
                    <?php foreach ($galleryItems as $item): ?>
                        <div class="gallery-item masonry-item" data-category="<?php echo $item['category']; ?>">
                            <div class="gallery-card">
                                <img src="<?php echo $item['image'] ? 'backend/uploads/gallery/' . $item['image'] : 'assets/images/placeholder-gallery.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                     class="gallery-image"
                                     loading="lazy">
                                
                                <div class="gallery-overlay">
                                    <div class="gallery-content">
                                        <div class="gallery-category">
                                            <span class="category-badge"><?php echo $galleryCategories[$item['category']]; ?></span>
                                        </div>
                                        <h5 class="gallery-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                        <p class="gallery-description"><?php echo htmlspecialchars($item['description']); ?></p>
                                        <button class="btn btn-primary btn-view-image" 
                                                data-image="<?php echo $item['image'] ? 'backend/uploads/gallery/' . $item['image'] : 'assets/images/placeholder-gallery.jpg'; ?>"
                                                data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                                data-description="<?php echo htmlspecialchars($item['description']); ?>">
                                            <i class="fas fa-expand"></i> Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- About Gallery Section -->
    <section class="section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h2>Cerita di Balik Setiap Foto</h2>
                    <p>Setiap foto dalam galeri kami menceritakan kisah passion dan dedikasi para petani kopi Desa Cepoko. Dari proses penanaman yang dilakukan dengan penuh kasih sayang, hingga pemetikan buah kopi yang dilakukan secara selektif untuk menjamin kualitas terbaik.</p>
                    <p>Kami bangga memperlihatkan proses tradisional yang masih dipertahankan hingga kini, karena kami percaya bahwa kualitas terbaik hanya bisa dihasilkan melalui proses yang tepat dan dedikasi yang tinggi.</p>
                    <div class="stats-grid" style="margin-top: 2rem;">
                        <div class="stat-item">
                            <h3 style="color: var(--primary-color); margin: 0;"><?php echo count($galleryItems); ?>+</h3>
                            <p style="margin: 0;">Foto Dokumentasi</p>
                        </div>
                        <div class="stat-item">
                            <h3 style="color: var(--primary-color); margin: 0;">30+</h3>
                            <p style="margin: 0;">Tahun Pengalaman</p>
                        </div>
                        <div class="stat-item">
                            <h3 style="color: var(--primary-color); margin: 0;">100%</h3>
                            <p style="margin: 0;">Proses Natural</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="highlight-images">
                        <div class="highlight-grid">
                            <?php 
                            $highlights = array_slice($galleryItems, 0, 4);
                            foreach ($highlights as $highlight): 
                            ?>
                                <div class="highlight-item">
                                    <img src="<?php echo $highlight['image'] ? 'backend/uploads/gallery/' . $highlight['image'] : 'assets/images/placeholder-gallery.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($highlight['title']); ?>"
                                         style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Overview -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Kategori Galeri</h2>
            <div class="categories-overview">
                <div class="row">
                    <div class="col-3">
                        <div class="category-card">
                            <i class="fas fa-seedling" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                            <h4>Perkebunan</h4>
                            <p>Dokumentasi perkebunan kopi di lereng Desa Cepoko dengan pemandangan alam yang indah dan tanaman kopi yang subur.</p>
                            <a href="galeri.php?category=plantation" class="btn btn-secondary">Lihat Foto</a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="category-card">
                            <i class="fas fa-cogs" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                            <h4>Proses Pengolahan</h4>
                            <p>Tahapan demi tahapan proses pengolahan kopi dari pemetikan, fermentasi, pengeringan, hingga roasting.</p>
                            <a href="galeri.php?category=process" class="btn btn-secondary">Lihat Foto</a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="category-card">
                            <i class="fas fa-coffee" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                            <h4>Produk</h4>
                            <p>Berbagai varian produk kopi Cepoko yang siap untuk dinikmati dengan kemasan yang menarik dan berkualitas.</p>
                            <a href="galeri.php?category=product" class="btn btn-secondary">Lihat Foto</a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="category-card">
                            <i class="fas fa-camera" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                            <h4>Lainnya</h4>
                            <p>Momen-momen spesial lainnya seperti pelatihan petani, kegiatan komunitas, dan event-event menarik.</p>
                            <a href="galeri.php?category=other" class="btn btn-secondary">Lihat Foto</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section bg-primary" style="color: white;">
        <div class="container text-center">
            <h2>Tertarik dengan Kopi Cepoko?</h2>
            <p>Lihat produk kami dan rasakan sendiri kualitas premium dari Desa Cepoko</p>
            <div class="mt-4">
                <a href="produk.php" class="btn btn-secondary">Lihat Produk</a>
                <a href="https://wa.me/<?php echo $whatsappNumber; ?>?text=Halo%20Kopi%20Cepoko,%20saya%20tertarik%20dengan%20produk%20Anda" 
                   class="btn btn-whatsapp" target="_blank">
                    <i class="fab fa-whatsapp"></i> Chat WhatsApp
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
    
    .category-filters {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .gallery-grid.masonry {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        grid-gap: 20px;
        margin: 2rem 0;
    }
    
    .gallery-item {
        break-inside: avoid;
        margin-bottom: 20px;
    }
    
    .gallery-card {
        position: relative;
        overflow: hidden;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        background: white;
    }
    
    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }
    
    .gallery-image {
        width: 100%;
        height: auto;
        display: block;
        transition: all 0.3s ease;
    }
    
    .gallery-card:hover .gallery-image {
        transform: scale(1.05);
    }
    
    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.9));
        color: white;
        padding: 1.5rem;
        transform: translateY(100%);
        transition: all 0.3s ease;
    }
    
    .gallery-card:hover .gallery-overlay {
        transform: translateY(0);
    }
    
    .gallery-category {
        margin-bottom: 0.5rem;
    }
    
    .category-badge {
        background: var(--accent-color);
        color: white;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .gallery-title {
        margin: 0.5rem 0;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .gallery-description {
        margin: 0.5rem 0 1rem;
        font-size: 0.875rem;
        opacity: 0.9;
        line-height: 1.4;
    }
    
    .btn-view-image {
        font-size: 0.875rem;
        padding: 8px 16px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 1rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
    }
    
    .highlight-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .categories-overview .row {
        margin-top: 2rem;
    }
    
    .category-card {
        text-align: center;
        padding: 2rem 1rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }
    
    .category-card h4 {
        margin: 1rem 0;
        color: var(--text-color);
    }
    
    .category-card p {
        color: var(--text-light);
        margin-bottom: 1.5rem;
        line-height: 1.6;
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
        
        .gallery-grid.masonry {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            grid-gap: 15px;
        }
        
        .gallery-overlay {
            position: relative;
            transform: none;
            background: rgba(0,0,0,0.8);
            margin-top: -4px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .highlight-grid {
            grid-template-columns: 1fr;
        }
        
        .category-filters {
            flex-direction: column;
            align-items: center;
        }
    }
    
    /* Lightbox Modal */
    .lightbox-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 2000;
    }
    
    .lightbox-content {
        max-width: 90vw;
        max-height: 90vh;
        text-align: center;
        position: relative;
    }
    
    .lightbox-image {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: var(--border-radius);
    }
    
    .lightbox-info {
        color: white;
        padding: 1rem;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .lightbox-close {
        position: absolute;
        top: -40px;
        right: 0;
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        padding: 10px;
    }
    
    .lightbox-close:hover {
        color: #ccc;
    }
    </style>
    
    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="lightbox-modal">
        <div class="lightbox-content">
            <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
            <img id="lightboxImage" class="lightbox-image" src="" alt="">
            <div class="lightbox-info">
                <h4 id="lightboxTitle"></h4>
                <p id="lightboxDescription"></p>
            </div>
        </div>
    </div>
    
    <script>
    // Gallery lightbox functionality
    document.addEventListener('DOMContentLoaded', function() {
        const viewButtons = document.querySelectorAll('.btn-view-image');
        const lightboxModal = document.getElementById('lightboxModal');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxTitle = document.getElementById('lightboxTitle');
        const lightboxDescription = document.getElementById('lightboxDescription');
        
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const imageSrc = this.getAttribute('data-image');
                const title = this.getAttribute('data-title');
                const description = this.getAttribute('data-description');
                
                lightboxImage.src = imageSrc;
                lightboxTitle.textContent = title;
                lightboxDescription.textContent = description;
                lightboxModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        });
        
        // Close lightbox when clicking outside
        lightboxModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });
        
        // Close lightbox with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && lightboxModal.style.display === 'flex') {
                closeLightbox();
            }
        });
    });
    
    function closeLightbox() {
        const lightboxModal = document.getElementById('lightboxModal');
        lightboxModal.style.display = 'none';
        document.body.style.overflow = '';
    }
    </script>
</body>
</html>