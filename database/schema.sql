
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'super_admin') DEFAULT 'admin',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Website content management
CREATE TABLE IF NOT EXISTS site_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_name VARCHAR(50) NOT NULL,
    section_name VARCHAR(50) NOT NULL,
    content_key VARCHAR(100) NOT NULL,
    content_value TEXT,
    content_type ENUM('text', 'html', 'image', 'json') DEFAULT 'text',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_content (page_name, section_name, content_key)
);

-- Product categories
CREATE TABLE IF NOT EXISTS product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    short_description VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    weight DECIMAL(8,2) DEFAULT 0,
    image VARCHAR(255),
    gallery JSON,
    specifications JSON,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE SET NULL
);

-- Gallery table
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255) NOT NULL,
    category ENUM('plantation', 'process', 'product', 'other') DEFAULT 'other',
    is_featured TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100),
    customer_address TEXT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Website settings
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'json', 'boolean') DEFAULT 'text',
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, email, password, full_name, role) VALUES 
('admin', 'admin@kopicepoko.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'super_admin');

-- Insert default site content
INSERT INTO site_content (page_name, section_name, content_key, content_value, content_type) VALUES
('home', 'hero', 'title', 'Kopi Cepoko Asli Desa Cepoko', 'text'),
('home', 'hero', 'subtitle', 'Cita Rasa Autentik dari Tanah Jawa', 'text'),
('home', 'hero', 'description', 'Nikmati kelezatan kopi premium dari perkebunan tradisional Desa Cepoko dengan cita rasa yang telah diwariskan turun temurun.', 'text'),
('home', 'about', 'title', 'Tentang Kopi Cepoko', 'text'),
('home', 'about', 'content', 'Kopi Cepoko adalah warisan budaya dari Desa Cepoko yang telah dibudidayakan secara tradisional selama puluhan tahun. Dengan tanah vulkanis yang subur dan iklim tropis yang ideal, kopi kami memiliki karakteristik rasa yang unik dan autentik.', 'html'),
('tentang', 'heritage', 'title', 'Warisan Kopi Desa Cepoko', 'text'),
('tentang', 'heritage', 'content', 'Desa Cepoko terletak di lereng pegunungan dengan ketinggian yang ideal untuk budidaya kopi. Tradisi menanam kopi di desa ini sudah berlangsung sejak zaman kolonial Belanda. Para petani di Desa Cepoko mewarisi teknik-teknik tradisional yang telah terbukti menghasilkan biji kopi berkualitas tinggi.', 'html');

-- Insert default product categories
INSERT INTO product_categories (name, description, is_active, sort_order) VALUES
('Kopi Bubuk', 'Kopi bubuk siap seduh dengan berbagai tingkat roasting', 1, 1),
('Kopi Biji', 'Biji kopi mentah dan roasted untuk keperluan khusus', 1, 2),
('Kopi Kemasan', 'Produk kopi dalam kemasan praktis dan ready to drink', 1, 3);

-- Insert sample products
INSERT INTO products (category_id, name, description, short_description, price, stock_quantity, weight, is_featured, is_active, sort_order) VALUES
(1, 'Kopi Cepoko Original', 'Kopi bubuk premium dengan roasting medium dari biji pilihan Desa Cepoko. Memiliki aroma yang khas dan rasa yang seimbang antara asam dan pahit.', 'Kopi bubuk premium roasting medium', 35000, 100, 250, 1, 1, 1),
(1, 'Kopi Cepoko Dark Roast', 'Kopi bubuk dengan tingkat roasting gelap yang memberikan rasa pahit yang kuat dan aroma yang pekat. Cocok untuk pecinta kopi hitam.', 'Kopi bubuk dark roast dengan rasa kuat', 37000, 75, 250, 1, 1, 2),
(2, 'Biji Kopi Cepoko Green Bean', 'Biji kopi mentah pilihan dari perkebunan Desa Cepoko. Ideal untuk roasting sendiri atau keperluan kafe dan kedai kopi.', 'Biji kopi mentah premium grade A', 280000, 50, 1000, 0, 1, 3),
(3, 'Kopi Cepoko Sachet', 'Kopi instan dalam kemasan sachet praktis. Cocok untuk dibawa traveling atau disimpan di kantor.', 'Kopi instan kemasan sachet (10 pcs)', 25000, 200, 100, 0, 1, 4);

-- Insert default gallery items
INSERT INTO gallery (title, description, category, is_featured, sort_order) VALUES
('Perkebunan Kopi Cepoko', 'Pemandangan perkebunan kopi di lereng Desa Cepoko dengan tanaman kopi yang subur', 'plantation', 1, 1),
('Proses Pemetikan Buah Kopi', 'Petani sedang memetik buah kopi merah yang sudah matang sempurna', 'process', 1, 2),
('Penjemuran Biji Kopi', 'Proses penjemuran biji kopi secara tradisional di bawah sinar matahari', 'process', 0, 3),
('Produk Kopi Siap Jual', 'Berbagai varian produk kopi Cepoko yang siap dipasarkan', 'product', 0, 4);

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES
('site_title', 'Kopi Cepoko - UMKM Desa Cepoko', 'text', 'Judul website'),
('site_description', 'Kopi premium dari Desa Cepoko dengan cita rasa autentik dan tradisional', 'text', 'Deskripsi website'),
('contact_phone', '08123456789', 'text', 'Nomor WhatsApp untuk kontak'),
('contact_email', 'info@kopicepoko.com', 'text', 'Email kontak'),
('contact_address', 'Desa Cepoko, Kecamatan Cepoko, Kabupaten Boyolali, Jawa Tengah', 'text', 'Alamat lengkap'),
('whatsapp_number', '628123456789', 'text', 'Nomor WhatsApp (format internasional)'),
('instagram_url', 'https://instagram.com/kopicepoko', 'text', 'URL Instagram'),
('facebook_url', 'https://facebook.com/kopicepoko', 'text', 'URL Facebook'),
('operating_hours', 'Senin - Sabtu: 08:00 - 17:00 WIB', 'text', 'Jam operasional'),
('shipping_info', 'Gratis ongkir untuk pembelian minimal Rp 100.000', 'text', 'Informasi pengiriman');