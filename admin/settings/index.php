<?php
session_start();
$pageTitle = 'Pengaturan Website';
$currentPage = 'settings';
require_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../../backend/functions/admin.php';
$contentManager = new ContentManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['settings'] as $key => $value) {
        $contentManager->updateSetting($key, $value);
    }
    $successMessage = 'Pengaturan berhasil diperbarui.';
}

$settings = $contentManager->getSettings();
?>

<?php if (isset($successMessage)): ?>
    <div class="alert-admin alert-success-admin">
        <?php echo $successMessage; ?>
    </div>
<?php endif; ?>

<div class="admin-form">
    <form method="POST" action="index.php">
        <?php foreach ($settings as $setting): ?>
            <div class="form-group">
                <label for="<?php echo $setting['setting_key']; ?>"><?php echo htmlspecialchars($setting['description']); ?></label>
                <input type="text" id="<?php echo $setting['setting_key']; ?>" name="settings[<?php echo $setting['setting_key']; ?>]" class="form-control" value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn-admin btn-primary-admin">Simpan Pengaturan</button>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>