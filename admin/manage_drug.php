<?php
include 'includes/session.php';
include 'includes/db.php';
include 'includes/header.php';

// Handle delete
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM drugs WHERE id = $id");
    header("Location: manage_drug.php");
    exit();
}

$drugs = $conn->query("SELECT * FROM drugs ORDER BY id DESC");
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-pills me-2"></i>Manage Drugs</h2>
        <a href="add_drug.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Drug
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Dosage</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Expiry Date</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $drugs->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['brand']); ?></td>
                            <td><?php echo htmlspecialchars($row['dosage']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $row['quantity'] > 10 ? 'success' : ($row['quantity'] > 0 ? 'warning' : 'danger'); ?>">
                                    <?php echo $row['quantity']; ?>
                                </span>
                            </td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['expiry_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td>
                                <a href="edit_drug.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="manage_drug.php?delete=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this drug?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 