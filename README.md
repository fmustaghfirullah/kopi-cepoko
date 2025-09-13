# Panduan Instalasi Website Kopi Cepoko

## Deskripsi
Website UMKM Kopi Cepoko adalah sistem manajemen konten lengkap untuk bisnis kopi yang terdiri dari:
- Frontend website yang responsif
- Admin dashboard untuk mengelola konten
- Backend PHP dengan database MySQL
- Integrasi WhatsApp untuk pemesanan

## Requirements Sistem

### Minimum Requirements
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **PHP**: Version 7.4 atau lebih baru (direkomendasikan PHP 8.0+)
- **Database**: MySQL 5.7+ atau MariaDB 10.3+
- **Storage**: Minimal 100MB ruang disk
- **Memory**: Minimal 128MB RAM

### PHP Extensions yang Diperlukan
- PDO
- PDO_MySQL
- GD (untuk image processing)
- JSON
- Session
- MBString

## Instalasi di Hostinger

### 1. Persiapan File
1. Download semua file dari folder `kopi-cepoko`
2. Compress menjadi file ZIP

### 2. Upload ke Hosting
1. Login ke hPanel Hostinger
2. Buka **File Manager**
3. Masuk ke folder `public_html`
4. Upload file ZIP dan extract
5. Pindahkan semua file dari folder `kopi-cepoko` ke root `public_html`

### 3. Setup Database
1. Di hPanel, buka **Databases** > **MySQL Databases**
2. Buat database baru (misal: `u123456789_kopi_cepoko`)
3. Buat user database dan berikan priviledge penuh
4. Catat informasi database:
   - Database Name
   - Username
   - Password
   - Hostname (biasanya `localhost`)

### 4. Import Database
1. Buka **phpMyAdmin** dari hPanel
2. Pilih database yang telah dibuat
3. Klik tab **Import**
4. Upload file `database/schema.sql`
5. Klik **Go** untuk mengimport

### 5. Konfigurasi Database
Edit file `backend/config/database.php`:

```php
// Ganti dengan informasi database Anda
const DB_HOST = 'localhost';
const DB_NAME = 'u123456789_kopi_cepoko'; // Nama database Anda
const DB_USER = 'u123456789_user';        // Username database
const DB_PASS = 'your_password_here';     // Password database

// Ganti dengan domain Anda
const SITE_URL = 'https://yourdomain.com';
const ADMIN_URL = 'https://yourdomain.com/admin';
```

### 6. Set Permissions
Pastikan folder berikut memiliki permission 755:
- `backend/uploads/`
- `backend/uploads/products/`
- `backend/uploads/gallery/`

## Instalasi di Localhost (XAMPP/WAMP)

### 1. Setup XAMPP
1. Download dan install XAMPP
2. Start Apache dan MySQL
3. Buka `http://localhost/phpmyadmin`

### 2. Buat Database
1. Buat database baru: `kopi_cepoko`
2. Import file `database/schema.sql`

### 3. Copy Files
1. Copy folder `kopi-cepoko` ke `C:\xampp\htdocs\`
2. Akses melalui `http://localhost/kopi-cepoko`

### 4. Konfigurasi (localhost)
File `backend/config/database.php` sudah dikonfigurasi untuk localhost:

```php
const DB_HOST = 'localhost';
const DB_NAME = 'kopi_cepoko';
const DB_USER = 'root';
const DB_PASS = '';
const SITE_URL = 'http://localhost/kopi-cepoko';
```

## Konfigurasi WhatsApp

### 1. Setting Nomor WhatsApp
Edit di Admin Dashboard > Pengaturan:
- **WhatsApp Number**: Format internasional (628123456789)
- **Contact Phone**: Format lokal (08123456789)

### 2. Testing WhatsApp Integration
1. Klik tombol WhatsApp di website
2. Pastikan membuka WhatsApp Web/App
3. Pesan template sudah terisi otomatis

## Admin Dashboard

### 1. Login Admin
- URL: `https://yourdomain.com/admin`
- **Username**: `admin`
- **Password**: `admin123`

**PENTING**: Segera ganti password default!

### 2. Fitur Admin Dashboard
- **Dashboard**: Statistik dan overview
- **Kelola Konten**: Edit konten halaman
- **Kelola Produk**: CRUD produk kopi
- **Kelola Galeri**: Upload dan manage foto
- **Kelola Pesanan**: Monitor pesanan masuk
- **Pengaturan**: Konfigurasi website

### 3. Ganti Password Admin
1. Login ke dashboard
2. Buka **Pengaturan** > **Admin**
3. Ganti password default

## Optimasi untuk Production

### 1. Keamanan
```php
// Tambahkan di .htaccess
RewriteEngine On

# Blokir akses ke file sensitif
<Files "*.php">
    Order Deny,Allow
    Deny from all
    Allow from localhost
</Files>

# Proteksi admin folder
<Directory "admin">
    AuthType Basic
    AuthName "Admin Area"
    AuthUserFile /path/to/.htpasswd
    Require valid-user
</Directory>
```

### 2. Performance
- Enable gzip compression
- Optimize images
- Use CDN untuk static files
- Enable browser caching

### 3. SEO
- Submit sitemap ke Google
- Setup Google Analytics
- Optimize meta tags
- Enable SSL certificate

## Backup & Maintenance

### 1. Backup Database
```bash
# Manual backup
mysqldump -u username -p kopi_cepoko > backup_$(date +%Y%m%d).sql

# Restore backup
mysql -u username -p kopi_cepoko < backup_20241201.sql
```

### 2. Backup Files
- Backup folder `backend/uploads/` secara berkala
- Keep backup di lokasi terpisah

### 3. Update Routine
- Update content secara berkala
- Monitor error logs
- Check broken links
- Update product information

## Troubleshooting

### 1. Database Connection Error
- Periksa kredensial database
- Pastikan MySQL service running
- Check hostname (localhost vs IP)

### 2. Image Upload Error
- Periksa permission folder uploads (755)
- Check PHP max_file_size
- Pastikan GD extension enabled

### 3. WhatsApp Not Working
- Periksa format nomor WhatsApp
- Test dengan nomor yang berbeda
- Pastikan URL encoding benar

### 4. Admin Login Issues
- Clear browser cache
- Check session configuration
- Verify database connection

## Support & Contact

Untuk bantuan lebih lanjut:
- Email: support@kopicepoko.com
- WhatsApp: +62 812-3456-789
- Documentation: [Link ke docs]

## License

Copyright (c) 2024 Kopi Cepoko
All rights reserved.

---

## File Structure
```
kopi-cepoko/
├── index.php              # Homepage
├── tentang.php            # About page
├── produk.php             # Products catalog
├── galeri.php             # Gallery
├── kontak.php             # Contact page
├── admin/                 # Admin dashboard
│   ├── index.php         # Admin login
│   ├── dashboard.php     # Admin dashboard
│   ├── logout.php        # Logout
│   ├── content/          # Content management
│   ├── products/         # Product management
│   ├── gallery/          # Gallery management
│   ├── orders/           # Order management
│   └── settings/         # Settings
├── backend/               # Backend functions
│   ├── config/           # Configuration
│   ├── functions/        # PHP functions
│   └── uploads/          # Uploaded files
├── assets/               # Frontend assets
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript
│   └── images/          # Images
├── database/            # Database files
│   └── schema.sql       # Database structure
└── README.md           # This file
```

## Next Steps
1. Customize branding dan colors
2. Add more products
3. Setup email notifications
4. Integrate payment gateway
5. Add analytics tracking
6. Implement caching
7. Setup automated backups