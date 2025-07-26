<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM staff WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_role'] = $row['role'];
            header("Location: dashboard.php");
            exit();
        }
    }
    $error = "Invalid credentials!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pharmacy MS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #4CAF50;
            --accent-color: #FF6B35;
            --dark-bg: #1a1a2e;
            --card-bg: #16213e;
            --text-light: #ffffff;
            --text-muted: #b8b8b8;
        }
        
        body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #0f3460 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header .logo {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }
        
        .login-header h2 {
            color: var(--text-light);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-floating .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: var(--text-light);
            padding: 15px 20px;
        }
        
        .form-floating .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
            color: var(--text-light);
        }
        
        .form-floating label {
            color: var(--text-muted);
            padding: 15px 20px;
        }
        
        .form-floating .form-control:focus + label,
        .form-floating .form-control:not(:placeholder-shown) + label {
            color: var(--secondary-color);
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }
        
        .btn-login {
            background: linear-gradient(45deg, var(--secondary-color), #45a049);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-login:hover {
            background: linear-gradient(45deg, #45a049, var(--secondary-color));
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
            border-left: 4px solid #dc3545;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .login-footer a {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .login-footer a:hover {
            color: #45a049;
        }
        
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .particle {
            position: absolute;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <!-- Animated Background Particles -->
    <div class="particles">
        <div class="particle" style="width: 20px; height: 20px; left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="width: 15px; height: 15px; left: 20%; animation-delay: 2s;"></div>
        <div class="particle" style="width: 25px; height: 25px; left: 30%; animation-delay: 4s;"></div>
        <div class="particle" style="width: 18px; height: 18px; left: 40%; animation-delay: 1s;"></div>
        <div class="particle" style="width: 22px; height: 22px; left: 50%; animation-delay: 3s;"></div>
        <div class="particle" style="width: 16px; height: 16px; left: 60%; animation-delay: 5s;"></div>
        <div class="particle" style="width: 24px; height: 24px; left: 70%; animation-delay: 2s;"></div>
        <div class="particle" style="width: 19px; height: 19px; left: 80%; animation-delay: 4s;"></div>
        <div class="particle" style="width: 21px; height: 21px; left: 90%; animation-delay: 1s;"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-pills"></i>
            </div>
            <h2>Admin Login</h2>
            <p>Pharmacy Management System</p>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
            </div>
            
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
            </div>
            
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login to Admin Panel
            </button>
        </form>
        
        <div class="login-footer">
            <a href="../index.php">
                <i class="fas fa-arrow-left me-1"></i>Back to Main Site
            </a>
            <span class="mx-2">|</span>
            <a href="../setup_admin.php">
                <i class="fas fa-user-plus me-1"></i>Create Admin Account
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 