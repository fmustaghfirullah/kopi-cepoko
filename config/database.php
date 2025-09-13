<?php
// Database configuration for Kopi Cepoko website
// Designed for Hostinger deployment

class DatabaseConfig {
    // Database configuration constants
    const DB_HOST = 'localhost';        // Hostinger MySQL server
    const DB_NAME = 'kopi_cepoko';      // Database name
    const DB_USER = 'root';             // Database username (change for production)
    const DB_PASS = '';                 // Database password (change for production)
    const DB_CHARSET = 'utf8mb4';       // Character set
    
    // Site configuration
    const SITE_URL = 'http://localhost/kopi-cepoko';  // Change to your domain
    const ADMIN_URL = 'http://localhost/kopi-cepoko/admin';
    const UPLOAD_PATH = '../backend/uploads/';
    const UPLOAD_URL = 'backend/uploads/';
    
    // Security settings
    const SESSION_TIMEOUT = 3600; // 1 hour
    const MAX_FILE_SIZE = 5242880; // 5MB
    const ALLOWED_IMAGE_TYPES = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    // WhatsApp settings
    const WHATSAPP_NUMBER = '628123456789'; // Format: 62xxx (Indonesia)
    
    // Pagination settings
    const ITEMS_PER_PAGE = 12;
    const ADMIN_ITEMS_PER_PAGE = 20;
}

// Database connection class
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DatabaseConfig::DB_HOST . 
                   ";dbname=" . DatabaseConfig::DB_NAME . 
                   ";charset=" . DatabaseConfig::DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->connection = new PDO($dsn, DatabaseConfig::DB_USER, DatabaseConfig::DB_PASS, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization
    public function __wakeup() {}
}

// Helper function to get database connection
function getDB() {
    return Database::getInstance()->getConnection();
}

// Start session with security settings
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
        ini_set('session.use_strict_mode', 1);
        session_start();
    }
}

// Check if user is logged in (for admin pages)
function isLoggedIn() {
    startSecureSession();
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

// Redirect to login if not authenticated
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../index.php');
        exit();
    }
}

// Sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Generate CSRF token
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Format currency (Indonesian Rupiah)
function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Format date for Indonesian locale
function formatDate($date, $format = 'd M Y') {
    $months = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];
    
    $timestamp = is_string($date) ? strtotime($date) : $date;
    $formatted = date($format, $timestamp);
    
    foreach ($months as $num => $month) {
        $formatted = str_replace(date('M', mktime(0, 0, 0, $num, 1)), $month, $formatted);
    }
    
    return $formatted;
}

// Upload file function
function uploadFile($file, $directory, $allowedTypes = null) {
    if ($allowedTypes === null) {
        $allowedTypes = DatabaseConfig::ALLOWED_IMAGE_TYPES;
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload error occurred'];
    }
    
    if ($file['size'] > DatabaseConfig::MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File size too large (max 5MB)'];
    }
    
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedTypes)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
    $uploadPath = DatabaseConfig::UPLOAD_PATH . $directory . '/' . $fileName;
    
    if (!is_dir(dirname($uploadPath))) {
        mkdir(dirname($uploadPath), 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return ['success' => true, 'filename' => $fileName, 'path' => $uploadPath];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
}

// Get site setting value
function getSiteSetting($key, $default = '') {
    $db = getDB();
    $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['setting_value'] : $default;
}

// Get site content
function getSiteContent($page, $section, $key, $default = '') {
    $db = getDB();
    $stmt = $db->prepare("SELECT content_value FROM site_content WHERE page_name = ? AND section_name = ? AND content_key = ?");
    $stmt->execute([$page, $section, $key]);
    $result = $stmt->fetch();
    return $result ? $result['content_value'] : $default;
}

// Error handler
function handleError($message) {
    error_log($message);
    if (ini_get('display_errors')) {
        echo "Error: " . htmlspecialchars($message);
    } else {
        echo "An error occurred. Please try again later.";
    }
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');
?>