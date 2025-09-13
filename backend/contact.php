<?php
// Backend contact handler
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

try {
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $subject = sanitizeInput($_POST['subject'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    
    // Validation
    if (empty($name) || empty($phone) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Nama, nomor telepon, dan pesan wajib diisi.']);
        exit();
    }
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Format email tidak valid.']);
        exit();
    }
    
    // Save to database
    $db = getDB();
    $sql = "INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    
    if ($stmt->execute([$name, $email, $phone, $subject, $message])) {
        echo json_encode(['success' => true, 'message' => 'Pesan berhasil dikirim!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan pesan.']);
    }
    
} catch (Exception $e) {
    error_log('Contact form error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem.']);
}
?>