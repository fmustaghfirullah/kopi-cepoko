<?php
require_once __DIR__ . '/../../backend/functions/admin.php';

$galleryId = $_GET['id'] ?? null;
if (!$galleryId) {
    header('Location: index.php');
    exit();
}

$galleryManager = new GalleryManager();
if ($galleryManager->deleteGalleryItem($galleryId)) {
    header('Location: index.php');
    exit();
} else {
    // Handle error, maybe set a session message
    header('Location: index.php');
    exit();
}
?>