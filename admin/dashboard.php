<?php
include 'includes/session.php';
include 'includes/db.php';

// Set page title
$page_title = "Dashboard";

include 'includes/header.php';

// Fetch counts for dashboard
$medicines = $conn->query("SELECT COUNT(*) as total FROM drugs")->fetch_assoc()['total'] ?? 0;
$staff = $conn->query("SELECT COUNT(*) as total FROM staff")->fetch_assoc()['total'] ?? 0;
$orders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'] ?? 0;
$customers = $conn->query("SELECT COUNT(*) as total FROM customers")->fetch_assoc()['total'] ?? 0;
$prescriptions = $conn->query("SELECT COUNT(*) as total FROM prescriptions")->fetch_assoc()['total'] ?? 0;
$complaints = $conn->query("SELECT COUNT(*) as total FROM complaints")->fetch_assoc()['total'] ?? 0;

// Get recent orders
$recent_orders = $conn->query("SELECT o.*, c.name as customer_name FROM orders o JOIN customers c ON o.customer_id = c.id ORDER BY o.order_date DESC LIMIT 5");

// Get low stock medicines
$low_stock = $conn->query("SELECT * FROM drugs WHERE quantity <= 10 ORDER BY quantity ASC LIMIT 5");
?>

<div class="row g-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Medicines</h6>
                        <h2 class="mb-0"><?php echo $medicines; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-pills"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Staff</h6>
                        <h2 class="mb-0"><?php echo $staff; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Orders</h6>
                        <h2 class="mb-0"><?php echo $orders; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Customers</h6>
                        <h2 class="mb-0"><?php echo $customers; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Stats -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-secondary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Prescriptions</h6>
                        <h2 class="mb-0"><?php echo $prescriptions; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-file-medical"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Complaints</h6>
                        <h2 class="mb-0"><?php echo $complaints; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-comments"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Recent Orders
                </h5>
            </div>
            <div class="card-body">
                <?php if($recent_orders->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($order = $recent_orders->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $order['status'] == 'Completed' ? 'success' : ($order['status'] == 'Pending' ? 'warning' : 'info'); ?>">
                                            <?php echo $order['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">No orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Alert -->
    <div class="col-lg-4">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                </h5>
            </div>
            <div class="card-body">
                <?php if($low_stock->num_rows > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php while($drug = $low_stock->fetch_assoc()): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1"><?php echo htmlspecialchars($drug['name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($drug['brand']); ?></small>
                            </div>
                            <span class="badge bg-danger rounded-pill"><?php echo $drug['quantity']; ?> left</span>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">All medicines are well stocked.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="add_drug.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Drug
                    </a>
                    <a href="add_staff.php" class="btn btn-success">
                        <i class="fas fa-user-plus me-2"></i>Add New Staff
                    </a>
                    <a href="manage_orders.php" class="btn btn-info">
                        <i class="fas fa-shopping-cart me-2"></i>View All Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 