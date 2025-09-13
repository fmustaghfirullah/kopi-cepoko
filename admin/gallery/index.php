<?php
$pageTitle = 'Kelola Galeri';
$currentPage = 'gallery';
require_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../../backend/functions/admin.php';
$galleryManager = new GalleryManager();

$galleryItems = $galleryManager->getGalleryItems();
?>

<div class="admin-table">
    <div class="table-header">
        <h3>Daftar Galeri</h3>
        <a href="add.php" class="btn-admin btn-primary-admin">
            <i class="fas fa-plus"></i> Tambah Item Galeri
        </a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($galleryItems)): ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Belum ada item galeri.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($galleryItems as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                        <td>
                            <span class="status-badge <?php echo $item['is_featured'] ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $item['is_featured'] ? 'Unggulan' : 'Normal'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="edit.php?id=<?php echo $item['id']; ?>" class="btn-admin btn-sm btn-warning-admin">Edit</a>
                                <a href="delete.php?id=<?php echo $item['id']; ?>" class="btn-admin btn-sm btn-danger-admin" onclick="return confirm('Yakin ingin menghapus item ini?');">Hapus</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>