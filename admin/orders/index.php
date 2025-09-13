<?php
$pageTitle = 'Kelola Pesanan';
$currentPage = 'orders';
require_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../../backend/functions/admin.php';
$orderManager = new OrderManager();

$orders = $orderManager->getOrders();
?>

<div class="admin-table">
    <div class="table-header">
        <h3>Daftar Pesanan</h3>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada pesanan.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo formatCurrency($order['total_amount']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td><?php echo formatDate($order['created_at']); ?></td>
                        <td>
                            <a href="view.php?id=<?php echo $order['id']; ?>" class="btn-admin btn-sm btn-primary-admin">Lihat</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>