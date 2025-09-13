<?php
$pageTitle = 'Tambah Produk';
require_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../../backend/functions/admin.php';
$productManager = new ProductManager();
$categories = $productManager->getCategories();

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
        'image' => '' // Handle file upload separately
    ];

    if ($productManager->addProduct($data)) {
        header('Location: index.php');
        exit();
    } else {
        $errorMessage = 'Gagal menambahkan produk.';
    }
}
?>

<div class="admin-form">
    <form method="POST" action="add.php" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="category_id">Kategori</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" rows="5"></textarea>
        </div>
        
        <div class="form-group">
            <label for="short_description">Deskripsi Singkat</label>
            <input type="text" id="short_description" name="short_description" class="form-control">
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="price">Harga</label>
                    <input type="number" id="price" name="price" class="form-control" required>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="stock_quantity">Stok</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" required>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="weight">Berat (gram)</label>
                    <input type="number" id="weight" name="weight" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="image">Gambar Produk</label>
            <input type="file" id="image" name="image" class="form-control-file">
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <div class="checkbox-group">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1">
                    <label for="is_featured">Tampilkan di Halaman Depan</label>
                </div>
            </div>
            <div class="form-col">
                <div class="checkbox-group">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                    <label for="is_active">Aktifkan Produk</label>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="sort_order">Urutan</label>
            <input type="number" id="sort_order" name="sort_order" class="form-control" value="0">
        </div>
        
        <button type="submit" class="btn-admin btn-primary-admin">Tambah Produk</button>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>