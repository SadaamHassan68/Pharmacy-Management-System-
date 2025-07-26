<?php
include 'includes/session.php';
include 'includes/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$staff_member = $conn->query("SELECT * FROM staff WHERE id = $id")->fetch_assoc();

if (!$staff_member) {
    header("Location: manage_staff.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone is required";
    }
    
    if (empty($role)) {
        $errors[] = "Role is required";
    }
    
    // Check if email already exists (excluding current staff member)
    $check_sql = "SELECT id FROM staff WHERE email = ? AND id != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $email, $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $errors[] = "Email already exists";
    }
    
    // Password validation (only if password is being changed)
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters";
        }
        
        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match";
        }
    }
    
    if (empty($errors)) {
        if (!empty($password)) {
            // Update with new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE staff SET name = ?, email = ?, phone = ?, role = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $name, $email, $phone, $role, $hashed_password, $id);
        } else {
            // Update without changing password
            $sql = "UPDATE staff SET name = ?, email = ?, phone = ?, role = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $name, $email, $phone, $role, $id);
        }
        
        if ($stmt->execute()) {
            $success = "Staff member updated successfully!";
            // Refresh staff member data
            $staff_member = $conn->query("SELECT * FROM staff WHERE id = $id")->fetch_assoc();
        } else {
            $errors[] = "Error updating staff member. Please try again.";
        }
    }
}
?>
<h2>Edit Staff Member</h2>

<?php if(isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="name" 
                                   value="<?php echo htmlspecialchars($staff_member['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" 
                                   value="<?php echo htmlspecialchars($staff_member['email']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone *</label>
                            <input type="tel" class="form-control" name="phone" 
                                   value="<?php echo htmlspecialchars($staff_member['phone']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role *</label>
                            <select name="role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="Admin" <?php echo ($staff_member['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="Pharmacist" <?php echo ($staff_member['role'] == 'Pharmacist') ? 'selected' : ''; ?>>Pharmacist</option>
                                <option value="Delivery" <?php echo ($staff_member['role'] == 'Delivery') ? 'selected' : ''; ?>>Delivery</option>
                                <option value="Cashier" <?php echo ($staff_member['role'] == 'Cashier') ? 'selected' : ''; ?>>Cashier</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" name="password">
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update Staff Member</button>
                        <a href="manage_staff.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Staff Information</h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> <?php echo $staff_member['id']; ?></p>
                <p><strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($staff_member['created_at'])); ?></p>
                <p><strong>Current Role:</strong> 
                    <span class="badge bg-<?php 
                        echo $staff_member['role'] == 'Admin' ? 'danger' : 
                            ($staff_member['role'] == 'Pharmacist' ? 'primary' : 
                            ($staff_member['role'] == 'Delivery' ? 'success' : 'secondary')); 
                    ?>">
                        <?php echo htmlspecialchars($staff_member['role']); ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 