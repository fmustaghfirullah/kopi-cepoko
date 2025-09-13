<?php
// Admin functions for Kopi Cepoko website
require_once(__DIR__ . '/../../config/database.php');


class AdminManager {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    // Authenticate admin user
    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM admin_users WHERE username = ? AND is_active = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            startSecureSession();
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_name'] = $user['full_name'];
            $_SESSION['admin_role'] = $user['role'];
            return true;
        }
        return false;
    }
    
    // Logout admin user
    public function logout() {
        startSecureSession();
        session_destroy();
    }
    
    // Get dashboard statistics
    public function getDashboardStats() {
        $stats = [];
        
        // Total products
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM products WHERE is_active = 1");
        $stats['total_products'] = $stmt->fetch()['count'];
        
        // Total orders
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM orders");
        $stats['total_orders'] = $stmt->fetch()['count'];
        
        // Pending orders
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
        $stats['pending_orders'] = $stmt->fetch()['count'];
        
        // Total gallery items
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM gallery");
        $stats['total_gallery'] = $stmt->fetch()['count'];
        
        // Total contact messages
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
        $stats['unread_messages'] = $stmt->fetch()['count'];
        
        // Recent orders
        $stmt = $this->db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
        $stats['recent_orders'] = $stmt->fetchAll();
        
        return $stats;
    }
}

class ProductManager {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    // Get all products with pagination
    public function getProducts($page = 1, $limit = null, $category_id = null, $search = null) {
        $limit = $limit ?: DatabaseConfig::ADMIN_ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        $where = "WHERE 1=1";
        $params = [];
        
        if ($category_id) {
            $where .= " AND p.category_id = ?";
            $params[] = $category_id;
        }
        
        if ($search) {
            $where .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
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
    
    // Get total products count
    public function getTotalProducts($category_id = null, $search = null) {
        $where = "WHERE 1=1";
        $params = [];
        
        if ($category_id) {
            $where .= " AND category_id = ?";
            $params[] = $category_id;
        }
        
        if ($search) {
            $where .= " AND (name LIKE ? OR description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM products $where");
        $stmt->execute($params);
        return $stmt->fetch()['count'];
    }
    
    // Get product by ID
    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN product_categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Add new product
    public function addProduct($data) {
        $sql = "INSERT INTO products (category_id, name, description, short_description, price, stock_quantity, weight, image, is_featured, is_active, sort_order) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['category_id'], $data['name'], $data['description'], $data['short_description'],
            $data['price'], $data['stock_quantity'], $data['weight'], $data['image'],
            $data['is_featured'], $data['is_active'], $data['sort_order']
        ]);
    }
    
    // Update product
    public function updateProduct($id, $data) {
        $sql = "UPDATE products SET category_id = ?, name = ?, description = ?, short_description = ?, 
                price = ?, stock_quantity = ?, weight = ?, image = ?, is_featured = ?, is_active = ?, sort_order = ?, updated_at = NOW() 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['category_id'], $data['name'], $data['description'], $data['short_description'],
            $data['price'], $data['stock_quantity'], $data['weight'], $data['image'],
            $data['is_featured'], $data['is_active'], $data['sort_order'], $id
        ]);
    }
    
    // Delete product
    public function deleteProduct($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Get all categories
    public function getCategories() {
        $stmt = $this->db->query("SELECT * FROM product_categories WHERE is_active = 1 ORDER BY sort_order, name");
        return $stmt->fetchAll();
    }
}

class GalleryManager {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    // Get all gallery items with pagination
    public function getGalleryItems($page = 1, $limit = null, $category = null) {
        $limit = $limit ?: DatabaseConfig::ADMIN_ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        $where = "WHERE 1=1";
        $params = [];
        
        if ($category) {
            $where .= " AND category = ?";
            $params[] = $category;
        }
        
        $sql = "SELECT * FROM gallery $where ORDER BY sort_order, created_at DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Get total gallery items count
    public function getTotalGalleryItems($category = null) {
        $where = "WHERE 1=1";
        $params = [];
        
        if ($category) {
            $where .= " AND category = ?";
            $params[] = $category;
        }
        
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM gallery $where");
        $stmt->execute($params);
        return $stmt->fetch()['count'];
    }
    
    // Add gallery item
    public function addGalleryItem($data) {
        $sql = "INSERT INTO gallery (title, description, image, category, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['title'], $data['description'], $data['image'], $data['category'], $data['is_featured'], $data['sort_order']]);
    }
    
    // Update gallery item
    public function updateGalleryItem($id, $data) {
        $sql = "UPDATE gallery SET title = ?, description = ?, image = ?, category = ?, is_featured = ?, sort_order = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['title'], $data['description'], $data['image'], $data['category'], $data['is_featured'], $data['sort_order'], $id]);
    }
    
    // Delete gallery item
    public function deleteGalleryItem($id) {
        $stmt = $this->db->prepare("DELETE FROM gallery WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

class OrderManager {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    // Get all orders with pagination
    public function getOrders($page = 1, $limit = null, $status = null) {
        $limit = $limit ?: DatabaseConfig::ADMIN_ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        $where = "WHERE 1=1";
        $params = [];
        
        if ($status) {
            $where .= " AND status = ?";
            $params[] = $status;
        }
        
        $sql = "SELECT * FROM orders $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Get order details with items
    public function getOrderDetails($id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        
        if ($order) {
            $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmt->execute([$id]);
            $order['items'] = $stmt->fetchAll();
        }
        
        return $order;
    }
    
    // Update order status
    public function updateOrderStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}

class ContentManager {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    // Get site content
    public function getContent($page, $section = null, $key = null) {
        $where = "WHERE page_name = ?";
        $params = [$page];
        
        if ($section) {
            $where .= " AND section_name = ?";
            $params[] = $section;
        }
        
        if ($key) {
            $where .= " AND content_key = ?";
            $params[] = $key;
        }
        
        $stmt = $this->db->prepare("SELECT * FROM site_content $where ORDER BY section_name, content_key");
        $stmt->execute($params);
        return $key ? $stmt->fetch() : $stmt->fetchAll();
    }
    
    // Update site content
    public function updateContent($page, $section, $key, $value, $type = 'text') {
        $sql = "INSERT INTO site_content (page_name, section_name, content_key, content_value, content_type) 
                VALUES (?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE content_value = ?, updated_at = NOW()";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$page, $section, $key, $value, $type, $value]);
    }
    
    // Get site settings
    public function getSettings() {
        $stmt = $this->db->query("SELECT * FROM site_settings ORDER BY setting_key");
        return $stmt->fetchAll();
    }
    
    // Update site setting
    public function updateSetting($key, $value, $type = 'text') {
        $sql = "INSERT INTO site_settings (setting_key, setting_value, setting_type) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = NOW()";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$key, $value, $type, $value]);
    }
}
?>