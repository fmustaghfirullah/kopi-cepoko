<?php
require_once 'backend/functions/frontend.php';

// Handle form submission
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $subject = sanitizeInput($_POST['subject'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    
    // Validation
    if (empty($name) || empty($phone) || empty($message)) {
        $errorMessage = 'Nama, nomor telepon, dan pesan wajib diisi.';
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Format email tidak valid.';
    } else {
        // Save to database
        $contactData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        ];
        
        if ($frontendManager->saveContactMessage($contactData)) {
            $successMessage = 'Pesan Anda berhasil dikirim! Kami akan menghubungi Anda segera.';
            
            // Clear form data
            $name = $email = $phone = $subject = $message = '';
        } else {
            $errorMessage = 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.';
        }
    }
}

// Get site settings
$whatsappNumber = getSiteSetting('whatsapp_number', '628123456789');
$contactPhone = getSiteSetting('contact_phone', '08123456789');
$contactEmail = getSiteSetting('contact_email', 'info@kopicepoko.com');
$contactAddress = getSiteSetting('contact_address', 'Desa Cepoko, Kecamatan Cepoko, Kabupaten Boyolali, Jawa Tengah');
$operatingHours = getSiteSetting('operating_hours', 'Senin - Sabtu: 08:00 - 17:00 WIB');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle('kontak', 'Kontak - Kopi Cepoko'); ?></title>
    <meta name="description" content="Hubungi Kopi Cepoko untuk pemesanan, informasi produk, atau pertanyaan lainnya melalui WhatsApp, telepon, atau email">
    <meta name="keywords" content="kontak kopi cepoko, pesan kopi, whatsapp kopi cepoko, telepon kopi cepoko">
    
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
                    <li><a href="galeri.php" class="nav-link">Galeri</a></li>
                    <li><a href="kontak.php" class="nav-link active">Kontak</a></li>
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
                    <li class="breadcrumb-item active" aria-current="page">Kontak</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="hero" style="padding: 3rem 0;">
        <div class="container">
            <h1>Hubungi Kami</h1>
            <p>Kami siap membantu Anda dengan informasi produk dan pemesanan</p>
        </div>
    </section>

    <!-- Quick Contact Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Cara Cepat Menghubungi Kami</h2>
            
            <div class="quick-contact-grid">
                <div class="row">
                    <div class="col-4">
                        <div class="contact-card whatsapp-card">
                            <div class="contact-icon">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <h4>WhatsApp</h4>
                            <p>Chat langsung untuk pemesanan dan informasi produk</p>
                            <p class="contact-detail"><?php echo $contactPhone; ?></p>
                            <a href="https://wa.me/<?php echo $whatsappNumber; ?>?text=Halo%20Kopi%20Cepoko,%20saya%20tertarik%20dengan%20produk%20Anda" 
                               class="btn btn-whatsapp" target="_blank">
                                <i class="fab fa-whatsapp"></i> Chat Sekarang
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-4">
                        <div class="contact-card phone-card">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h4>Telepon</h4>
                            <p>Hubungi kami langsung untuk konsultasi</p>
                            <p class="contact-detail"><?php echo $contactPhone; ?></p>
                            <a href="tel:<?php echo $contactPhone; ?>" class="btn btn-primary">
                                <i class="fas fa-phone"></i> Telepon
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-4">
                        <div class="contact-card email-card">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h4>Email</h4>
                            <p>Kirim email untuk pertanyaan detail</p>
                            <p class="contact-detail"><?php echo $contactEmail; ?></p>
                            <a href="mailto:<?php echo $contactEmail; ?>" class="btn btn-secondary">
                                <i class="fas fa-envelope"></i> Kirim Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form and Info Section -->
    <section class="section bg-light">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-6">
                    <div class="contact-form-container">
                        <h3>Kirim Pesan</h3>
                        <p>Isi formulir di bawah ini dan kami akan merespons secepatnya</p>
                        
                        <?php if ($successMessage): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <?php echo $successMessage; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($errorMessage): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> <?php echo $errorMessage; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="kontak.php" class="contact-form">
                            <div class="form-group">
                                <label for="name">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" 
                                       value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Nomor Telepon/WhatsApp <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" class="form-control" 
                                       value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email (Opsional)</label>
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Subjek</label>
                                <select id="subject" name="subject" class="form-control">
                                    <option value="">Pilih subjek...</option>
                                    <option value="Pemesanan Produk" <?php echo ($subject ?? '') === 'Pemesanan Produk' ? 'selected' : ''; ?>>Pemesanan Produk</option>
                                    <option value="Informasi Produk" <?php echo ($subject ?? '') === 'Informasi Produk' ? 'selected' : ''; ?>>Informasi Produk</option>
                                    <option value="Kerjasama" <?php echo ($subject ?? '') === 'Kerjasama' ? 'selected' : ''; ?>>Kerjasama</option>
                                    <option value="Keluhan" <?php echo ($subject ?? '') === 'Keluhan' ? 'selected' : ''; ?>>Keluhan</option>
                                    <option value="Lainnya" <?php echo ($subject ?? '') === 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Pesan <span class="required">*</span></label>
                                <textarea id="message" name="message" class="form-control" rows="5" 
                                          placeholder="Tulis pesan Anda di sini..." required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="col-6">
                    <div class="contact-info">
                        <h3>Informasi Kontak</h3>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h5>Alamat</h5>
                                <p><?php echo htmlspecialchars($contactAddress); ?></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h5>Jam Operasional</h5>
                                <p><?php echo htmlspecialchars($operatingHours); ?></p>
                                <p class="text-muted">Minggu: Tutup</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="info-content">
                                <h5>Pengiriman</h5>
                                <p><?php echo getSiteSetting('shipping_info', 'Gratis ongkir untuk pembelian minimal Rp 100.000'); ?></p>
                                <p class="text-muted">Pengiriman ke seluruh Indonesia</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="info-content">
                                <h5>Customer Service</h5>
                                <p>Respon cepat melalui WhatsApp</p>
                                <p class="text-muted">Rata-rata respon: &lt; 1 jam</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="social-media-section">
                        <h4>Ikuti Kami</h4>
                        <div class="social-links-large">
                            <a href="<?php echo getSiteSetting('instagram_url', '#'); ?>" target="_blank" class="social-link instagram">
                                <i class="fab fa-instagram"></i>
                                <span>Instagram</span>
                            </a>
                            <a href="<?php echo getSiteSetting('facebook_url', '#'); ?>" target="_blank" class="social-link facebook">
                                <i class="fab fa-facebook"></i>
                                <span>Facebook</span>
                            </a>
                            <a href="https://wa.me/<?php echo $whatsappNumber; ?>" target="_blank" class="social-link whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Lokasi Kami</h2>
            <div class="map-container">
                <div class="map-placeholder">
                    <div class="map-content">
                        <i class="fas fa-map-marker-alt" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h4>Desa Cepoko</h4>
                        <p><?php echo htmlspecialchars($contactAddress); ?></p>
                        <button class="btn btn-primary" onclick="openGoogleMaps()">
                            <i class="fas fa-map"></i> Buka di Google Maps
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section bg-light">
        <div class="container">
            <h2 class="section-title">Pertanyaan Sering Diajukan</h2>
            
            <div class="faq-container">
                <div class="row">
                    <div class="col-6">
                        <div class="faq-item">
                            <h5>Bagaimana cara memesan kopi Cepoko?</h5>
                            <p>Anda bisa memesan melalui WhatsApp, telepon, atau mengisi formulir kontak di website ini. Tim kami akan segera merespons pesanan Anda.</p>
                        </div>
                        
                        <div class="faq-item">
                            <h5>Apakah ada minimum order?</h5>
                            <p>Tidak ada minimum order. Namun untuk gratis ongkos kirim, minimal pembelian Rp 100.000.</p>
                        </div>
                        
                        <div class="faq-item">
                            <h5>Berapa lama pengiriman?</h5>
                            <p>Pengiriman dalam kota 1-2 hari, luar kota 2-5 hari kerja tergantung lokasi dan kurir yang dipilih.</p>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="faq-item">
                            <h5>Apakah bisa COD (Cash on Delivery)?</h5>
                            <p>Ya, kami melayani COD untuk area tertentu. Silakan konfirmasi dengan customer service kami.</p>
                        </div>
                        
                        <div class="faq-item">
                            <h5>Bagaimana cara penyimpanan kopi yang baik?</h5>
                            <p>Simpan kopi di tempat kering, sejuk, dan kedap udara. Hindari paparan sinar matahari langsung dan tempat lembab.</p>
                        </div>
                        
                        <div class="faq-item">
                            <h5>Apakah menerima pesanan dalam jumlah besar?</h5>
                            <p>Ya, kami melayani pesanan grosir dan korporat dengan harga khusus. Hubungi kami untuk detail lebih lanjut.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section bg-primary" style="color: white;">
        <div class="container text-center">
            <h2>Masih Ada Pertanyaan?</h2>
            <p>Jangan ragu untuk menghubungi kami. Tim customer service kami siap membantu Anda</p>
            <a href="https://wa.me/<?php echo $whatsappNumber; ?>?text=Halo%20Kopi%20Cepoko,%20saya%20ada%20pertanyaan%20tentang%20produk%20Anda" 
               class="btn btn-whatsapp" target="_blank">
                <i class="fab fa-whatsapp"></i> Chat di WhatsApp
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
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($contactAddress); ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo $contactPhone; ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo $contactEmail; ?></p>
                </div>
                
                <div class="footer-section">
                    <h4>Jam Operasional</h4>
                    <p><?php echo htmlspecialchars($operatingHours); ?></p>
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
    
    .quick-contact-grid {
        margin: 2rem 0;
    }
    
    .contact-card {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        height: 100%;
        transition: all 0.3s ease;
        border-top: 4px solid transparent;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }
    
    .whatsapp-card {
        border-top-color: #25D366;
    }
    
    .phone-card {
        border-top-color: var(--primary-color);
    }
    
    .email-card {
        border-top-color: var(--secondary-color);
    }
    
    .contact-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .whatsapp-card .contact-icon {
        color: #25D366;
    }
    
    .phone-card .contact-icon {
        color: var(--primary-color);
    }
    
    .email-card .contact-icon {
        color: var(--secondary-color);
    }
    
    .contact-detail {
        font-weight: 600;
        color: var(--text-color);
        margin: 1rem 0;
    }
    
    .contact-form-container {
        background: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
    }
    
    .contact-form .form-group {
        margin-bottom: 1.5rem;
    }
    
    .required {
        color: var(--danger-color);
    }
    
    .contact-info {
        padding: 2rem 0;
    }
    
    .info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 2rem;
        padding: 1rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
    }
    
    .info-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
        font-size: 1.25rem;
    }
    
    .info-content h5 {
        margin: 0 0 0.5rem 0;
        color: var(--text-color);
    }
    
    .info-content p {
        margin: 0;
        color: var(--text-light);
    }
    
    .social-media-section {
        margin-top: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
    }
    
    .social-links-large {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .social-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius);
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .social-link i {
        width: 24px;
        margin-right: 12px;
        font-size: 1.25rem;
    }
    
    .social-link.instagram {
        background: linear-gradient(45deg, #405DE6, #5B51D8, #833AB4, #C13584, #E1306C, #FD1D1D);
        color: white;
    }
    
    .social-link.facebook {
        background: #1877F2;
        color: white;
    }
    
    .social-link.whatsapp {
        background: #25D366;
        color: white;
    }
    
    .social-link:hover {
        transform: translateX(5px);
        box-shadow: var(--shadow);
        color: white;
    }
    
    .map-container {
        margin: 2rem 0;
    }
    
    .map-placeholder {
        height: 400px;
        background: #f8f9fa;
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: 2px dashed var(--border-color);
    }
    
    .map-content h4 {
        color: var(--text-color);
        margin: 0.5rem 0;
    }
    
    .faq-container {
        margin-top: 2rem;
    }
    
    .faq-item {
        background: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        margin-bottom: 1rem;
    }
    
    .faq-item h5 {
        color: var(--primary-color);
        margin-bottom: 0.75rem;
    }
    
    .faq-item p {
        margin: 0;
        color: var(--text-light);
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
        
        .col-4, .col-6 {
            max-width: 100%;
            margin-bottom: 1rem;
        }
        
        .contact-card {
            margin-bottom: 1rem;
        }
        
        .contact-form-container {
            margin-bottom: 2rem;
        }
        
        .social-links-large {
            gap: 0.5rem;
        }
        
        .info-item {
            flex-direction: column;
            text-align: center;
        }
        
        .info-icon {
            margin: 0 auto 1rem auto;
        }
    }
    </style>
    
    <script>
    function openGoogleMaps() {
        // Replace with actual coordinates of Desa Cepoko
        const lat = -7.5;
        const lng = 110.8;
        const url = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
        window.open(url, '_blank');
    }
    
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.contact-form');
        
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const message = document.getElementById('message').value.trim();
            
            if (!name || !phone || !message) {
                e.preventDefault();
                alert('Nama, nomor telepon, dan pesan wajib diisi.');
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            
            // Re-enable after timeout
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 3000);
        });
    });
    </script>
</body>
</html>