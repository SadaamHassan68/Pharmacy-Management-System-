<?php
include 'includes/session.php';
include 'includes/db.php';
include 'includes/header.php';

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($id != $_SESSION['admin_id']) { // Prevent admin from deleting themselves
        $conn->query("DELETE FROM staff WHERE id = $id");
        $success = "Staff member deleted successfully!";
    } else {
        $error = "You cannot delete your own account!";
    }
}

$staff = $conn->query("SELECT * FROM staff ORDER BY created_at DESC");
?>
<h2>Manage Staff</h2>

<?php if(isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="mb-3">
    <a href="add_staff.php" class="btn btn-primary">Add New Staff</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Created Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $staff->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td>
                    <span class="badge bg-<?php 
                        echo $row['role'] == 'Admin' ? 'danger' : 
                            ($row['role'] == 'Pharmacist' ? 'primary' : 
                            ($row['role'] == 'Delivery' ? 'success' : 'secondary')); 
                    ?>">
                        <?php echo htmlspecialchars($row['role']); ?>
                    </span>
                </td>
                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                <td>
                    <a href="edit_staff.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <?php if($row['id'] != $_SESSION['admin_id']): ?>
                        <a href="?action=delete&id=<?php echo $row['id']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure you want to delete this staff member?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?> 