<?php
// Frontend functions for Kopi Cepoko website
require_once 'backend/config/database.php';

class FrontendManager {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    // Get featured products for homepage
    public function getFeaturedProducts($limit = 6) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN product_categories c ON p.category_id = c.id WHERE p.is_featured = 1 AND p.is_active = 1 ORDER BY p.sort_order LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    // Get all products with pagination
    public function getProducts($page = 1, $category_id = null, $limit = null) {
        $limit = $limit ?: DatabaseConfig::ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        $where = "WHERE p.is_active = 1";
        $params = [];
        
        if ($category_id) {
            $where .= " AND p.category_id = ?";
            $params[] = $category_id;
        }
        
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN product_categories c ON p.category_id = c.id 
                $where 
                ORDER BY p.sort_order, p.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Get total products count for pagination
    public function getTotalProducts($category_id = null) {
        $where = "WHERE is_active = 1";
        $params = [];
        
        if ($category_id) {
            $where .= " AND category_id = ?";
            $params[] = $category_id;
        }
        
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM products $where");
        $stmt->execute($params);
        return $stmt->fetch()['count'];
    }
    
    // Get product details by ID
    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN product_categories c ON p.category_id = c.id WHERE p.id = ? AND p.is_active = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Get active product categories
    public function getCategories() {
        $stmt = $this->db->query("SELECT * FROM product_categories WHERE is_active = 1 ORDER BY sort_order, name");
        return $stmt->fetchAll();
    }
    
    // Get gallery items for gallery page
    public function getGalleryItems($category = null, $limit = null) {
        $where = "WHERE 1=1";
        $params = [];
        
        if ($category) {
            $where .= " AND category = ?";
            $params[] = $category;
        }
        
        $limitClause = $limit ? "LIMIT $limit" : "";
        
        $sql = "SELECT * FROM gallery $where ORDER BY sort_order, created_at DESC $limitClause";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Get featured gallery items for homepage
    public function getFeaturedGallery($limit = 6) {
        $stmt = $this->db->prepare("SELECT * FROM gallery WHERE is_featured = 1 ORDER BY sort_order LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    // Save contact message
    public function saveContactMessage($data) {
        $sql = "INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'], 
            $data['email'], 
            $data['phone'], 
            $data['subject'], 
            $data['message']
        ]);
    }
    
    // Create order (for WhatsApp ordering)
    public function createOrder($orderData, $items) {
        try {
            $this->db->beginTransaction();
            
            // Generate order number
            $orderNumber = 'KCP' . date('Ymd') . sprintf('%04d', rand(1, 9999));
            
            // Insert order
            $sql = "INSERT INTO orders (order_number, customer_name, customer_phone, customer_email, customer_address, total_amount, status, notes) 
                    VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $orderNumber,
                $orderData['customer_name'],
                $orderData['customer_phone'],
                $orderData['customer_email'],
                $orderData['customer_address'],
                $orderData['total_amount'],
                $orderData['notes']
            ]);
            
            $orderId = $this->db->lastInsertId();
            
            // Insert order items
            $itemSql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
            $itemStmt = $this->db->prepare($itemSql);
            
            foreach ($items as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['product_name'],
                    $item['quantity'],
                    $item['price'],
                    $item['subtotal']
                ]);
            }
            
            $this->db->commit();
            return ['success' => true, 'order_number' => $orderNumber, 'order_id' => $orderId];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // Search products
    public function searchProducts($query, $limit = null) {
        $limit = $limit ?: DatabaseConfig::ITEMS_PER_PAGE;
        
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN product_categories c ON p.category_id = c.id 
                WHERE p.is_active = 1 AND (p.name LIKE ? OR p.description LIKE ? OR p.short_description LIKE ?) 
                ORDER BY p.sort_order 
                LIMIT $limit";
        
        $searchTerm = "%$query%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
    
    // Get site statistics for homepage
    public function getSiteStats() {
        $stats = [];
        
        // Total products
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM products WHERE is_active = 1");
        $stats['total_products'] = $stmt->fetch()['count'];
        
        // Total categories
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM product_categories WHERE is_active = 1");
        $stats['total_categories'] = $stmt->fetch()['count'];
        
        // Total gallery items
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM gallery");
        $stats['total_gallery'] = $stmt->fetch()['count'];
        
        return $stats;
    }
}

// Helper functions for frontend

// Generate WhatsApp order message
function generateWhatsAppMessage($orderData, $items) {
    $message = "🌟 *PESANAN KOPI CEPOKO* 🌟\n\n";
    $message .= "📋 *Detail Pesanan:*\n";
    $message .= "Nama: " . $orderData['customer_name'] . "\n";
    $message .= "Telepon: " . $orderData['customer_phone'] . "\n";
    if (!empty($orderData['customer_email'])) {
        $message .= "Email: " . $orderData['customer_email'] . "\n";
    }
    if (!empty($orderData['customer_address'])) {
        $message .= "Alamat: " . $orderData['customer_address'] . "\n";
    }
    
    $message .= "\n🛒 *Produk yang Dipesan:*\n";
    $total = 0;
    foreach ($items as $item) {
        $subtotal = $item['quantity'] * $item['price'];
        $message .= "• " . $item['product_name'] . "\n";
        $message .= "  Qty: " . $item['quantity'] . " x " . formatCurrency($item['price']) . " = " . formatCurrency($subtotal) . "\n";
        $total += $subtotal;
    }
    
    $message .= "\n💰 *Total: " . formatCurrency($total) . "*\n";
    
    if (!empty($orderData['notes'])) {
        $message .= "\n📝 *Catatan:*\n" . $orderData['notes'] . "\n";
    }
    
    $message .= "\n🙏 Terima kasih telah memesan Kopi Cepoko!";
    
    return $message;
}

// Generate WhatsApp URL
function generateWhatsAppURL($message, $phone = null) {
    $phone = $phone ?: getSiteSetting('whatsapp_number', DatabaseConfig::WHATSAPP_NUMBER);
    $encodedMessage = urlencode($message);
    return "https://wa.me/$phone?text=$encodedMessage";
}

// Generate breadcrumb
function generateBreadcrumb($items) {
    $breadcrumb = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    
    foreach ($items as $index => $item) {
        if ($index === count($items) - 1) {
            $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($item['title']) . '</li>';
        } else {
            $breadcrumb .= '<li class="breadcrumb-item"><a href="' . htmlspecialchars($item['url']) . '">' . htmlspecialchars($item['title']) . '</a></li>';
        }
    }
    
    $breadcrumb .= '</ol></nav>';
    return $breadcrumb;
}

// Calculate pagination
function calculatePagination($currentPage, $totalItems, $itemsPerPage) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    
    return [
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'total_items' => $totalItems,
        'has_previous' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'previous_page' => $currentPage > 1 ? $currentPage - 1 : null,
        'next_page' => $currentPage < $totalPages ? $currentPage + 1 : null
    ];
}

// Get page title
function getPageTitle($page, $default = '') {
    $titles = [
        'home' => 'Beranda - Kopi Cepoko',
        'tentang' => 'Tentang Kami - Kopi Cepoko',
        'produk' => 'Produk Kami - Kopi Cepoko',
        'galeri' => 'Galeri - Kopi Cepoko',
        'kontak' => 'Kontak - Kopi Cepoko'
    ];
    
    return isset($titles[$page]) ? $titles[$page] : $default;
}

// Initialize frontend manager
$frontendManager = new FrontendManager();
?>