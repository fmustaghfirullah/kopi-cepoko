<?php
$pageTitle = 'Tambah Item Galeri';
require_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../../backend/functions/admin.php';
$galleryManager = new GalleryManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'category' => $_POST['category'],
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'sort_order' => $_POST['sort_order'],
        'image' => '' // Handle file upload separately
    ];

    if ($galleryManager->addGalleryItem($data)) {
        header('Location: index.php');
        exit();
    } else {
        $errorMessage = 'Gagal menambahkan item galeri.';
    }
}
?>

<div class="admin-form">
    <form method="POST" action="add.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Judul</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
        </div>
        
        <div class="form-group">
            <label for="category">Kategori</label>
            <select id="category" name="category" class="form-control" required>
                <option value="plantation">Perkebunan</option>
                <option value="process">Proses</option>
                <option value="product">Produk</option>
                <option value="other">Lainnya</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="image">Gambar</label>
            <input type="file" id="image" name="image" class="form-control-file" required>
        </div>
        
        <div class="checkbox-group">
            <input type="checkbox" id="is_featured" name="is_featured" value="1">
            <label for="is_featured">Tampilkan sebagai unggulan</label>
        </div>
        
        <div class="form-group">
            <label for="sort_order">Urutan</label>
            <input type="number" id="sort_order" name="sort_order" class="form-control" value="0">
        </div>
        
        <button type="submit" class="btn-admin btn-primary-admin">Tambah Item</button>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>