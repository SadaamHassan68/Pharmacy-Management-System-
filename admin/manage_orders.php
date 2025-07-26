<?php
include 'includes/session.php';
include 'includes/db.php';
include 'includes/header.php';

// Handle status update
if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    $allowed = ['Pending', 'Processing', 'Delivered', 'Cancelled'];
    if (in_array($status, $allowed)) {
        $conn->query("UPDATE orders SET status = '$status' WHERE id = $order_id");
        $success = "Order status updated!";
    }
}

// Filter by status
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$where = '';
if ($status_filter && in_array($status_filter, ['Pending', 'Processing', 'Delivered', 'Cancelled'])) {
    $where = "WHERE status = '$status_filter'";
}
$orders = $conn->query("SELECT o.*, c.name AS customer_name FROM orders o JOIN customers c ON o.customer_id = c.id $where ORDER BY o.order_date DESC");
?>
<h2>Manage Orders</h2>

<?php if(isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="mb-3">
    <form method="get" class="d-flex align-items-center">
        <label class="me-2">Filter by Status:</label>
        <select name="status" class="form-select w-auto me-2" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="Pending" <?php if($status_filter=='Pending') echo 'selected'; ?>>Pending</option>
            <option value="Processing" <?php if($status_filter=='Processing') echo 'selected'; ?>>Processing</option>
            <option value="Delivered" <?php if($status_filter=='Delivered') echo 'selected'; ?>>Delivered</option>
            <option value="Cancelled" <?php if($status_filter=='Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($order = $orders->fetch_assoc()): ?>
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td><?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?></td>
                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                <td>
                    <span class="badge bg-<?php 
                        echo $order['status'] == 'Pending' ? 'warning' : 
                            ($order['status'] == 'Processing' ? 'info' : 
                            ($order['status'] == 'Delivered' ? 'success' : 'secondary')); 
                    ?>">
                        <?php echo $order['status']; ?>
                    </span>
                </td>
                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $order['id']; ?>">View</button>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                            <option value="Pending" <?php if($order['status']=='Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Processing" <?php if($order['status']=='Processing') echo 'selected'; ?>>Processing</option>
                            <option value="Delivered" <?php if($order['status']=='Delivered') echo 'selected'; ?>>Delivered</option>
                            <option value="Cancelled" <?php if($order['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                    </form>
                </td>
            </tr>
            <!-- Order Details Modal -->
            <div class="modal fade" id="orderModal<?php echo $order['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Order #<?php echo $order['id']; ?> Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <?php
                            $order_items = $conn->query("
                                SELECT oi.*, d.name, d.brand 
                                FROM order_items oi 
                                JOIN drugs d ON oi.drug_id = d.id 
                                WHERE oi.order_id = " . $order['id']
                            );
                            ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Brand</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($item = $order_items->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td><?php echo htmlspecialchars($item['brand']); ?></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td>$<?php echo $item['price']; ?></td>
                                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?> 