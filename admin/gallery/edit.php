<?php
$pageTitle = 'Edit Item Galeri';
require_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../../backend/functions/admin.php';
$galleryManager = new GalleryManager();

$galleryId = $_GET['id'] ?? null;
if (!$galleryId) {
    header('Location: index.php');
    exit();
}

$item = $galleryManager->getGalleryItemById($galleryId); // Assuming getGalleryItemById exists

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'category' => $_POST['category'],
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'sort_order' => $_POST['sort_order'],
        'image' => $item['image'] // Handle file upload separately
    ];

    if ($galleryManager->updateGalleryItem($galleryId, $data)) {
        header('Location: index.php');
        exit();
    } else {
        $errorMessage = 'Gagal mengupdate item galeri.';
    }
}
?>

<div class="admin-form">
    <form method="POST" action="edit.php?id=<?php echo $galleryId; ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Judul</label>
            <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($item['title']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($item['description']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="category">Kategori</label>
            <select id="category" name="category" class="form-control" required>
                <option value="plantation" <?php echo ($item['category'] === 'plantation') ? 'selected' : ''; ?>>Perkebunan</option>
                <option value="process" <?php echo ($item['category'] === 'process') ? 'selected' : ''; ?>>Proses</option>
                <option value="product" <?php echo ($item['category'] === 'product') ? 'selected' : ''; ?>>Produk</option>
                <option value="other" <?php echo ($item['category'] === 'other') ? 'selected' : ''; ?>>Lainnya</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="image">Gambar</label>
            <input type="file" id="image" name="image" class="form-control-file">
            <?php if ($item['image']): ?>
                <div class="upload-preview">
                    <img src="<?php echo SITE_URL . '/backend/uploads/gallery/' . $item['image']; ?>" alt="Gallery Image">
                </div>
            <?php endif; ?>
        </div>
        
        <div class="checkbox-group">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo $item['is_featured'] ? 'checked' : ''; ?>>
            <label for="is_featured">Tampilkan sebagai unggulan</label>
        </div>
        
        <div class="form-group">
            <label for="sort_order">Urutan</label>
            <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?php echo $item['sort_order']; ?>">
        </div>
        
        <button type="submit" class="btn-admin btn-primary-admin">Update Item</button>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>