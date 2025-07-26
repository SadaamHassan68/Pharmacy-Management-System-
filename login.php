<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_name'] = $user['name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid credentials!";
        }
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pharmacy MS</title>
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
        .login-card { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 40px; }
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
        <li class="nav-item"><a class="nav-link active" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus me-1"></i>Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Header -->
<section class="page-header">
  <div class="container">
    <h1><i class="fas fa-sign-in-alt me-3"></i>Login</h1>
  </div>
</section>

<div class="container mb-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="login-card">
        <?php if (isset($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        
        <form method="POST">
          <div class="mb-4">
            <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          
          <div class="mb-4">
            <label for="password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
          </div>
        </form>
        
        <hr class="my-4">
        
        <div class="text-center">
          <p class="mb-3">Don't have an account?</p>
          <a href="register.php" class="btn btn-outline-primary">
            <i class="fas fa-user-plus me-2"></i>Create Account
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