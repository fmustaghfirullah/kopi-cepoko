<?php
$pageTitle = 'Edit Produk';
require_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../../backend/functions/admin.php';
$productManager = new ProductManager();
$categories = $productManager->getCategories();

$productId = $_GET['id'] ?? null;
if (!$productId) {
    header('Location: index.php');
    exit();
}

$product = $productManager->getProductById($productId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'category_id' => $_POST['category_id'],
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'short_description' => $_POST['short_description'],
        'price' => $_POST['price'],
        'stock_quantity' => $_POST['stock_quantity'],
        'weight' => $_POST['weight'],
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'sort_order' => $_POST['sort_order'],
        'image' => $product['image'] // Handle file upload separately
    ];

    if ($productManager->updateProduct($productId, $data)) {
        header('Location: index.php');
        exit();
    } else {
        $errorMessage = 'Gagal mengupdate produk.';
    }
}
?>

<div class="admin-form">
    <form method="POST" action="edit.php?id=<?php echo $productId; ?>" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="category_id">Kategori</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="short_description">Deskripsi Singkat</label>
            <input type="text" id="short_description" name="short_description" class="form-control" value="<?php echo htmlspecialchars($product['short_description']); ?>">
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="price">Harga</label>
                    <input type="number" id="price" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="stock_quantity">Stok</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="<?php echo $product['stock_quantity']; ?>" required>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="weight">Berat (gram)</label>
                    <input type="number" id="weight" name="weight" class="form-control" value="<?php echo $product['weight']; ?>">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="image">Gambar Produk</label>
            <input type="file" id="image" name="image" class="form-control-file">
            <?php if ($product['image']): ?>
                <div class="upload-preview">
                    <img src="<?php echo SITE_URL . '/backend/uploads/products/' . $product['image']; ?>" alt="Product Image">
                </div>
            <?php endif; ?>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <div class="checkbox-group">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo $product['is_featured'] ? 'checked' : ''; ?>>
                    <label for="is_featured">Tampilkan di Halaman Depan</label>
                </div>
            </div>
            <div class="form-col">
                <div class="checkbox-group">
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?php echo $product['is_active'] ? 'checked' : ''; ?>>
                    <label for="is_active">Aktifkan Produk</label>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="sort_order">Urutan</label>
            <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?php echo $product['sort_order']; ?>">
        </div>
        
        <button type="submit" class="btn-admin btn-primary-admin">Update Produk</button>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>