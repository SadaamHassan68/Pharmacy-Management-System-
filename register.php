<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO customers (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
            
            if ($insert_stmt->execute()) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed! Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pharmacy MS</title>
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
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: var(--dark-text); }
        .navbar-brand { font-weight: bold; font-size: 1.5rem; }
        .page-header { background: linear-gradient(135deg, var(--primary-color) 0%, #1e3c72 100%); color: white; padding: 60px 0; margin-bottom: 40px; }
        .page-header h1 { font-size: 2.5rem; font-weight: bold; margin-bottom: 15px; }
        .register-card { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 40px; }
        .form-control { border-radius: 10px; padding: 12px 15px; border: 2px solid #e9ecef; }
        .form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25); }
        .btn-primary { background: var(--primary-color); border: none; border-radius: 10px; padding: 12px 30px; font-weight: 600; }
        .btn-primary:hover { background: #1e3c72; }
        .footer { background: var(--dark-text); color: white; padding: 50px 0 20px; margin-top: 80px; }
        .footer h5 { color: var(--secondary-color); margin-bottom: 20px; }
        .footer a { color: #bdc3c7; text-decoration: none; }
        .footer a:hover { color: var(--secondary-color); }
        .social-icons a { display: inline-block; width: 40px; height: 40px; background: var(--primary-color); color: white; text-align: center; line-height: 40px; border-radius: 50%; margin-right: 10px; transition: background 0.3s ease; }
        .social-icons a:hover { background: var(--secondary-color); }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--primary-color);">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="fas fa-pills me-2"></i>Pharmacy MS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
        <li class="nav-item"><a class="nav-link" href="medicines.php"><i class="fas fa-pills me-1"></i>Medicines</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php"><i class="fas fa-info-circle me-1"></i>About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-envelope me-1"></i>Contact</a></li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
        <li class="nav-item"><a class="nav-link active" href="register.php"><i class="fas fa-user-plus me-1"></i>Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Header -->
<section class="page-header">
  <div class="container">
    <h1><i class="fas fa-user-plus me-3"></i>Create Account</h1>
  </div>
</section>

<div class="container mb-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="register-card">
        <?php if (isset($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        
        <form method="POST">
          <div class="row">
            <div class="col-md-12 mb-4">
              <label for="name" class="form-label"><i class="fas fa-user me-2"></i>Full Name</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-4">
              <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email Address</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="col-md-6 mb-4">
              <label for="phone" class="form-label"><i class="fas fa-phone me-2"></i>Phone Number</label>
              <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-4">
              <label for="password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="col-md-6 mb-4">
              <label for="confirm_password" class="form-label"><i class="fas fa-lock me-2"></i>Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
          </div>
          
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-user-plus me-2"></i>Create Account
            </button>
          </div>
        </form>
        
        <hr class="my-4">
        
        <div class="text-center">
          <p class="mb-3">Already have an account?</p>
          <a href="login.php" class="btn btn-outline-primary">
            <i class="fas fa-sign-in-alt me-2"></i>Login
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h5><i class="fas fa-pills me-2"></i>Pharmacy MS</h5>
        <p>Your trusted online pharmacy for quality medicines and healthcare products. We are committed to providing the best service to our customers.</p>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="col-md-2">
        <h5>Quick Links</h5>
        <ul class="list-unstyled">
          <li><a href="index.php">Home</a></li>
          <li><a href="medicines.php">Medicines</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h5>Customer Service</h5>
        <ul class="list-unstyled">
          <li><a href="order_history.php">Order History</a></li>
          <li><a href="complaint.php">Submit Complaint</a></li>
          <li><a href="upload_prescription.php">Upload Prescription</a></li>
          <li><a href="faq.php">FAQ</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h5>Contact Info</h5>
        <ul class="list-unstyled">
          <li><i class="fas fa-phone me-2"></i>+1 234 567 8900</li>
          <li><i class="fas fa-envelope me-2"></i>info@pharmacyms.com</li>
          <li><i class="fas fa-map-marker-alt me-2"></i>123 Pharmacy St, City</li>
        </ul>
      </div>
    </div>
    <hr class="my-4">
    <div class="text-center">
      <p>&copy; <?php echo date('Y'); ?> Pharmacy Management System. All rights reserved.</p>
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 