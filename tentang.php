<?php
require_once 'backend/functions/frontend.php';

// Get page content
$heritageTitle = getSiteContent('tentang', 'heritage', 'title', 'Warisan Kopi Desa Cepoko');
$heritageContent = getSiteContent('tentang', 'heritage', 'content', 'Desa Cepoko terletak di lereng pegunungan dengan ketinggian yang ideal untuk budidaya kopi. Tradisi menanam kopi di desa ini sudah berlangsung sejak zaman kolonial Belanda. Para petani di Desa Cepoko mewarisi teknik-teknik tradisional yang telah terbukti menghasilkan biji kopi berkualitas tinggi.');

// Get site settings
$whatsappNumber = getSiteSetting('whatsapp_number', '628123456789');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle('tentang', 'Tentang Kami - Kopi Cepoko'); ?></title>
    <meta name="description" content="Warisan kopi tradisional Desa Cepoko yang telah berlangsung turun temurun dengan cita rasa autentik">
    <meta name="keywords" content="sejarah kopi cepoko, desa cepoko, warisan kopi, kopi tradisional">
    
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
                    <li><a href="tentang.php" class="nav-link active">Tentang Kami</a></li>
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

    <!-- Breadcrumb -->
    <section class="breadcrumb-section" style="background: #f8f9fa; padding: 1rem 0;">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background: none; margin: 0; padding: 0;">
                    <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tentang Kami</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="hero" style="padding: 3rem 0;">
        <div class="container">
            <h1><?php echo htmlspecialchars($heritageTitle); ?></h1>
            <p>Mengenal lebih dekat perjalanan dan warisan kopi premium dari Desa Cepoko</p>
        </div>
    </section>

    <!-- Heritage Story Section -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h2>Sejarah Kopi Cepoko</h2>
                    <p><?php echo $heritageContent; ?></p>
                    <p>Dengan kondisi geografis yang mendukung dan pengalaman yang telah diwariskan turun temurun, petani kopi Desa Cepoko mampu menghasilkan biji kopi dengan karakteristik yang khas. Tanah vulkanis yang subur, ketinggian yang tepat, serta iklim tropis yang ideal membuat kopi dari desa ini memiliki cita rasa yang berbeda dari daerah lain.</p>
                    <p>Proses pemanenan masih dilakukan secara manual dengan memilih buah kopi yang benar-benar matang. Teknik pengolahan yang digunakan pun masih mempertahankan cara tradisional yang terbukti menghasilkan kualitas terbaik.</p>
                </div>
                <div class="col-6">
                    <img src="assets/images/heritage-story.jpg" alt="Sejarah Kopi Cepoko" style="width: 100%; border-radius: 8px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="section bg-light">
        <div class="container">
            <h2 class="section-title">Nilai-Nilai Kami</h2>
            <div class="row">
                <div class="col-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-leaf" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                            <h4>Kualitas Premium</h4>
                            <p>Kami hanya menggunakan biji kopi pilihan terbaik yang dipetik pada tingkat kematangan sempurna untuk menjamin cita rasa yang optimal.</p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-users" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                            <h4>Tradisi Turun Temurun</h4>
                            <p>Mempertahankan teknik-teknik tradisional yang telah terbukti selama puluhan tahun menghasilkan kopi berkualitas tinggi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-heart" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                            <h4>Dibuat dengan Cinta</h4>
                            <p>Setiap proses pembuatan kopi dilakukan dengan dedikasi dan cinta untuk menghadirkan pengalaman minum kopi yang tak terlupakan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Proses Pembuatan Kopi</h2>
            <p class="section-subtitle">Dari kebun hingga cangkir Anda</p>
            
            <div class="process-steps">
                <div class="row">
                    <div class="col-3">
                        <div class="process-step text-center">
                            <div class="step-icon">
                                <i class="fas fa-seedling" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                            </div>
                            <h4>1. Penanaman</h4>
                            <p>Bibit kopi pilihan ditanam di tanah vulkanis yang subur dengan ketinggian ideal untuk pertumbuhan optimal.</p>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="process-step text-center">
                            <div class="step-icon">
                                <i class="fas fa-hand-holding-heart" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                            </div>
                            <h4>2. Pemeliharaan</h4>
                            <p>Perawatan intensif dengan metode organik untuk menjaga kualitas tanaman dan kelestarian lingkungan.</p>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="process-step text-center">
                            <div class="step-icon">
                                <i class="fas fa-hand-paper" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                            </div>
                            <h4>3. Pemetikan</h4>
                            <p>Pemetikan manual buah kopi yang matang sempurna untuk menjamin kualitas biji yang dihasilkan.</p>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="process-step text-center">
                            <div class="step-icon">
                                <i class="fas fa-fire" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                            </div>
                            <h4>4. Pengolahan</h4>
                            <p>Proses pengeringan, sangrai, dan pengemasan dengan standar kualitas tinggi untuk cita rasa terbaik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section class="section bg-light">
        <div class="container">
            <h2 class="section-title">Lokasi Desa Cepoko</h2>
            <div class="row">
                <div class="col-6">
                    <h3>Kondisi Geografis Ideal</h3>
                    <ul style="line-height: 2;">
                        <li><strong>Ketinggian:</strong> 800-1200 mdpl</li>
                        <li><strong>Suhu:</strong> 18-25°C</li>
                        <li><strong>Curah Hujan:</strong> 1500-2500 mm/tahun</li>
                        <li><strong>Jenis Tanah:</strong> Vulkanis dengan pH 6-7</li>
                        <li><strong>Kelembaban:</strong> 70-80%</li>
                    </ul>
                    <p>Kondisi geografis ini sangat mendukung pertumbuhan tanaman kopi arabika yang menghasilkan biji berkualitas premium dengan aroma dan cita rasa yang khas.</p>
                </div>
                <div class="col-6">
                    <div class="location-map" style="background: #f0f0f0; padding: 2rem; border-radius: 8px; text-align: center;">
                        <i class="fas fa-map-marker-alt" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h4>Desa Cepoko</h4>
                        <p>Kecamatan Cepoko<br>
                        Kabupaten Boyolali<br>
                        Jawa Tengah, Indonesia</p>
                        <a href="#" class="btn btn-primary" onclick="openMap()">
                            <i class="fas fa-map"></i> Lihat Peta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Tim Kami</h2>
            <p class="section-subtitle">Orang-orang di balik kualitas Kopi Cepoko</p>
            
            <div class="row">
                <div class="col-4">
                    <div class="card text-center">
                        <img src="assets/images/team-1.jpg" alt="Pak Slamet" style="width: 150px; height: 150px; border-radius: 50%; margin: 1rem auto; object-fit: cover;">
                        <div class="card-body">
                            <h4>Pak Slamet</h4>
                            <h5 class="text-primary">Kepala Petani</h5>
                            <p>Petani kopi berpengalaman 30+ tahun yang mewarisi teknik tradisional dari orangtuanya.</p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-center">
                        <img src="assets/images/team-2.jpg" alt="Ibu Sari" style="width: 150px; height: 150px; border-radius: 50%; margin: 1rem auto; object-fit: cover;">
                        <div class="card-body">
                            <h4>Ibu Sari</h4>
                            <h5 class="text-primary">Quality Control</h5>
                            <p>Ahli dalam seleksi dan pengolahan biji kopi untuk menjamin kualitas produk yang konsisten.</p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-center">
                        <img src="assets/images/team-3.jpg" alt="Mas Budi" style="width: 150px; height: 150px; border-radius: 50%; margin: 1rem auto; object-fit: cover;">
                        <div class="card-body">
                            <h4>Mas Budi</h4>
                            <h5 class="text-primary">Marketing</h5>
                            <p>Mengelola pemasaran dan hubungan dengan pelanggan untuk memperkenalkan Kopi Cepoko ke masyarakat luas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section bg-primary" style="color: white;">
        <div class="container text-center">
            <h2>Coba Kopi Premium Desa Cepoko</h2>
            <p>Rasakan sendiri cita rasa autentik dan kualitas premium dari warisan tradisional kami</p>
            <div class="mt-4">
                <a href="produk.php" class="btn btn-secondary">Lihat Produk Kami</a>
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
    
    .process-step {
        margin-bottom: 2rem;
    }
    
    .step-icon {
        margin-bottom: 1rem;
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
        
        .col-3, .col-4, .col-6 {
            max-width: 100%;
            margin-bottom: 1rem;
        }
    }
    </style>
    
    <script>
    function openMap() {
        // Replace with actual coordinates of Desa Cepoko
        const lat = -7.5;
        const lng = 110.8;
        const url = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
        window.open(url, '_blank');
    }
    </script>
</body>
</html>