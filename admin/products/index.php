<?php
$pageTitle = 'Kelola Produk';
$currentPage = 'products';
require_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../../backend/functions/admin.php';
$productManager = new ProductManager();

$products = $productManager->getProducts();
?>

<div class="admin-table">
    <div class="table-header">
        <h3>Daftar Produk</h3>
        <a href="add.php" class="btn-admin btn-primary-admin">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada produk.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                        <td><?php echo formatCurrency($product['price']); ?></td>
                        <td><?php echo $product['stock_quantity']; ?></td>
                        <td>
                            <span class="status-badge <?php echo $product['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $product['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn-admin btn-sm btn-warning-admin">Edit</a>
                                <a href="delete.php?id=<?php echo $product['id']; ?>" class="btn-admin btn-sm btn-danger-admin" onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>