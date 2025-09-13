<?php
$pageTitle = 'Kelola Konten';
$currentPage = 'content';
require_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../../backend/functions/admin.php';
$contentManager = new ContentManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['content'] as $page => $sections) {
        foreach ($sections as $section => $keys) {
            foreach ($keys as $key => $value) {
                $contentManager->updateContent($page, $section, $key, $value);
            }
        }
    }
    $successMessage = 'Konten berhasil diperbarui.';
}

$homeContent = $contentManager->getContent('home');
$aboutContent = $contentManager->getContent('tentang');
?>

<?php if (isset($successMessage)): ?>
    <div class="alert-admin alert-success-admin">
        <?php echo $successMessage; ?>
    </div>
<?php endif; ?>

<div class="admin-form">
    <form method="POST" action="index.php">
        
        <h3>Halaman Depan (Home)</h3>
        <?php foreach ($homeContent as $content): ?>
            <div class="form-group">
                <label for="content_<?php echo $content['id']; ?>"><?php echo ucfirst($content['section_name']) . ' - ' . ucfirst($content['content_key']); ?></label>
                <input type="text" id="content_<?php echo $content['id']; ?>" name="content[home][<?php echo $content['section_name']; ?>][<?php echo $content['content_key']; ?>]" class="form-control" value="<?php echo htmlspecialchars($content['content_value']); ?>">
            </div>
        <?php endforeach; ?>
        
        <hr style="margin: 2rem 0;">
        
        <h3>Halaman Tentang Kami</h3>
        <?php foreach ($aboutContent as $content): ?>
            <div class="form-group">
                <label for="content_<?php echo $content['id']; ?>"><?php echo ucfirst($content['section_name']) . ' - ' . ucfirst($content['content_key']); ?></label>
                <textarea id="content_<?php echo $content['id']; ?>" name="content[tentang][<?php echo $content['section_name']; ?>][<?php echo $content['content_key']; ?>]" class="form-control" rows="5"><?php echo htmlspecialchars($content['content_value']); ?></textarea>
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn-admin btn-primary-admin">Simpan Konten</button>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>