<?php
$pageTitle = 'Detail Pesanan';
require_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../../backend/functions/admin.php';
$orderManager = new OrderManager();

$orderId = $_GET['id'] ?? null;
if (!$orderId) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $newStatus = $_POST['status'];
    $orderManager->updateOrderStatus($orderId, $newStatus);
    // Refresh the page to show the updated status
    header('Location: view.php?id=' . $orderId);
    exit();
}

$order = $orderManager->getOrderDetails($orderId);

if (!$order) {
    echo "<div class='alert-admin alert-danger-admin'>Pesanan tidak ditemukan.</div>";
    require_once __DIR__ . '/../partials/footer.php';
    exit();
}
?>

<div class="admin-form">
    <h3>Detail Pesanan #<?php echo htmlspecialchars($order['order_number']); ?></h3>
    
    <div class="order-details">
        <p><strong>Nama Pelanggan:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p><strong>Telepon:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
        <p><strong>Alamat:</strong> <?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></p>
        <p><strong>Total:</strong> <?php echo formatCurrency($order['total_amount']); ?></p>
        <p><strong>Tanggal:</strong> <?php echo formatDate($order['created_at']); ?></p>
        <p><strong>Status Saat Ini:</strong> <span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></p>
    </div>

    <hr style="margin: 2rem 0;">

    <h4>Item Pesanan</h4>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo formatCurrency($item['price']); ?></td>
                    <td><?php echo formatCurrency($item['subtotal']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr style="margin: 2rem 0;">

    <h4>Update Status Pesanan</h4>
    <form method="POST" action="view.php?id=<?php echo $orderId; ?>">
        <div class="form-group">
            <label for="status">Status Baru</label>
            <select id="status" name="status" class="form-control">
                <option value="pending" <?php echo ($order['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="confirmed" <?php echo ($order['status'] === 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                <option value="processing" <?php echo ($order['status'] === 'processing') ? 'selected' : ''; ?>>Processing</option>
                <option value="shipped" <?php echo ($order['status'] === 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                <option value="delivered" <?php echo ($order['status'] === 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                <option value="cancelled" <?php echo ($order['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" name="update_status" class="btn-admin btn-primary-admin">Update Status</button>
    </form>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>