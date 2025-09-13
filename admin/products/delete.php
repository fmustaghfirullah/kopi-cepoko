<?php
require_once __DIR__ . '/../../backend/functions/admin.php';

$productId = $_GET['id'] ?? null;
if (!$productId) {
    header('Location: index.php');
    exit();
}

$productManager = new ProductManager();
if ($productManager->deleteProduct($productId)) {
    header('Location: index.php');
    exit();
} else {
    // Handle error, maybe set a session message
    header('Location: index.php');
    exit();
}
?>