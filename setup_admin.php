<?php
session_start();
include 'includes/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($name)) {
        $error = "Admin name is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM staff WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Email already exists in database!";
        } else {
            // Hash password and insert admin
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO staff (name, email, phone, role, password) VALUES (?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("sssss", $name, $email, $phone, $role, $hashed_password);
            
            if ($insert_stmt->execute()) {
                $success = "Admin created successfully! You can now login with the provided credentials.";
            } else {
                $error = "Error creating admin: " . $conn->error;
            }
        }
    }
}

// Get existing admins
$admins = $conn->query("SELECT id, name, email, role, created_at FROM staff ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin - Pharmacy MS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #4CAF50;
            --accent-color: #FF6B35;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
        }
        
        .setup-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .terminal-style {
            background: #1e1e1e;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="setup-card">
                <div class="text-center mb-4">
                    <h2><i class="fas fa-user-shield me-2"></i>Admin Setup Tool</h2>
                    <p class="text-muted">Create new admin users for Pharmacy Management System</p>
                </div>
                
                <?php if($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Admin Name *</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="Admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'Admin') ? 'selected' : ''; ?>>Admin (Full Access)</option>
                                <option value="Manager" <?php echo (isset($_POST['role']) && $_POST['role'] == 'Manager') ? 'selected' : ''; ?>>Manager (Limited Access)</option>
                                <option value="Staff" <?php echo (isset($_POST['role']) && $_POST['role'] == 'Staff') ? 'selected' : ''; ?>>Staff (Basic Access)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Create Admin
                        </button>
                        <a href="admin/login.php" class="btn btn-outline-secondary">
                            <i class="fas fa-sign-in-alt me-2"></i>Go to Admin Login
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Terminal Style Instructions -->
            <div class="setup-card mt-4">
                <h4><i class="fas fa-terminal me-2"></i>Terminal Alternative</h4>
                <p>You can also create admins using the terminal script:</p>
                <div class="terminal-style">
                    <div>cd "C:\xampp\htdocs\pharmacy ms"</div>
                    <div>php create_admin.php</div>
                </div>
            </div>
            
            <!-- Existing Admins -->
            <?php if($admins->num_rows > 0): ?>
            <div class="setup-card mt-4">
                <h4><i class="fas fa-users me-2"></i>Existing Admins</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($admin = $admins->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($admin['name']); ?></td>
                                <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                <td><span class="badge bg-primary"><?php echo htmlspecialchars($admin['role']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($admin['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 