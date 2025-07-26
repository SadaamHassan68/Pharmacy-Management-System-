<?php
include 'includes/db.php';
session_start();

// Customer Analysis Data
$total_customers = $conn->query("SELECT COUNT(*) as total FROM customers")->fetch_assoc()['total'] ?? 0;
$total_orders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'] ?? 0;
$total_medicines = $conn->query("SELECT COUNT(*) as total FROM drugs")->fetch_assoc()['total'] ?? 0;
$total_complaints = $conn->query("SELECT COUNT(*) as total FROM complaints")->fetch_assoc()['total'] ?? 0;

// Recent customer registrations
$recent_customers = $conn->query("SELECT * FROM customers ORDER BY created_at DESC LIMIT 5");

// Popular medicine categories
$category_stats = $conn->query("SELECT category, COUNT(*) as count FROM drugs GROUP BY category ORDER BY count DESC LIMIT 5");

// Order status distribution
$order_stats = $conn->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Pharmacy MS</title>
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
            color: var(--dark-text);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e3c72 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 60px;
        }
        
        .page-header h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .about-section {
            background: var(--light-bg);
            padding: 60px 0;
        }
        
        .analysis-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .footer {
            background: var(--dark-text);
            color: white;
            padding: 50px 0 20px;
            margin-top: 80px;
        }
        
        .footer h5 {
            color: var(--secondary-color);
            margin-bottom: 20px;
        }
        
        .footer a {
            color: #bdc3c7;
            text-decoration: none;
        }
        
        .footer a:hover {
            color: var(--secondary-color);
        }
        
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            transition: background 0.3s ease;
        }
        
        .social-icons a:hover {
            background: var(--secondary-color);
        }
        
        .progress-bar-custom {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--primary-color);">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-pills me-2"></i>Pharmacy MS
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
        <li class="nav-item"><a class="nav-link" href="medicines.php"><i class="fas fa-pills me-1"></i>Medicines</a></li>
        <?php if(isset($_SESSION['customer_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart me-1"></i>Cart</a></li>
          <li class="nav-item"><a class="nav-link" href="order_history.php"><i class="fas fa-history me-1"></i>My Orders</a></li>
          <li class="nav-item"><a class="nav-link" href="complaint.php"><i class="fas fa-comment me-1"></i>Complaints</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if(isset($_SESSION['customer_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus me-1"></i>Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Header -->
<section class="page-header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1><i class="fas fa-info-circle me-3"></i>About Us</h1>
        <p class="lead">Learn more about our pharmacy management system and customer insights</p>
      </div>
      <div class="col-lg-4 text-center">
        <i class="fas fa-chart-line" style="font-size: 6rem; opacity: 0.3;"></i>
      </div>
    </div>
  </div>
</section>

<!-- Statistics Section -->
<section class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <div class="stats-card">
          <div class="stats-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="stats-number"><?php echo $total_customers; ?></div>
          <h5>Total Customers</h5>
          <p class="text-muted">Registered users</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card">
          <div class="stats-icon">
            <i class="fas fa-shopping-bag"></i>
          </div>
          <div class="stats-number"><?php echo $total_orders; ?></div>
          <h5>Total Orders</h5>
          <p class="text-muted">Orders placed</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card">
          <div class="stats-icon">
            <i class="fas fa-pills"></i>
          </div>
          <div class="stats-number"><?php echo $total_medicines; ?></div>
          <h5>Medicines</h5>
          <p class="text-muted">Available products</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card">
          <div class="stats-icon">
            <i class="fas fa-comments"></i>
          </div>
          <div class="stats-number"><?php echo $total_complaints; ?></div>
          <h5>Complaints</h5>
          <p class="text-muted">Customer feedback</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- About Section -->
<section class="about-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <h2 class="mb-4">Our Story</h2>
        <p class="lead">Pharmacy MS is a comprehensive online pharmacy management system designed to provide seamless healthcare services to our customers.</p>
        <p>Founded with the vision of making healthcare accessible to everyone, we have been serving our community with quality medicines and exceptional customer service. Our platform combines modern technology with traditional pharmacy values to deliver the best possible experience.</p>
        <p>We are committed to:</p>
        <ul>
          <li>Providing authentic and quality medicines</li>
          <li>Ensuring fast and secure delivery</li>
          <li>Maintaining customer privacy and data security</li>
          <li>Offering 24/7 customer support</li>
          <li>Continuous improvement of our services</li>
        </ul>
      </div>
      <div class="col-lg-6">
        <div class="analysis-card">
          <h4><i class="fas fa-chart-pie me-2"></i>Customer Analysis</h4>
          <p class="text-muted">Real-time insights into our pharmacy operations</p>
          
          <h6 class="mt-4">Medicine Categories Distribution</h6>
          <?php while($category = $category_stats->fetch_assoc()): ?>
          <div class="mb-3">
            <div class="d-flex justify-content-between">
              <span><?php echo htmlspecialchars($category['category']); ?></span>
              <span><?php echo $category['count']; ?> items</span>
            </div>
            <div class="progress">
              <div class="progress-bar progress-bar-custom" style="width: <?php echo ($category['count'] / $total_medicines) * 100; ?>%"></div>
            </div>
          </div>
          <?php endwhile; ?>
          
          <h6 class="mt-4">Order Status Distribution</h6>
          <?php while($order = $order_stats->fetch_assoc()): ?>
          <div class="mb-2">
            <div class="d-flex justify-content-between">
              <span><?php echo htmlspecialchars($order['status']); ?></span>
              <span><?php echo $order['count']; ?> orders</span>
            </div>
          </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Recent Customers Section -->
<section class="py-5">
  <div class="container">
    <h2 class="text-center mb-5">Recent Customer Registrations</h2>
    <div class="row">
      <?php while($customer = $recent_customers->fetch_assoc()): ?>
      <div class="col-md-4 mb-4">
        <div class="analysis-card">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-user-circle" style="font-size: 3rem; color: var(--primary-color);"></i>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6><?php echo htmlspecialchars($customer['name']); ?></h6>
              <p class="text-muted mb-1"><?php echo htmlspecialchars($customer['email']); ?></p>
              <small class="text-muted">Joined: <?php echo date('M d, Y', strtotime($customer['created_at'])); ?></small>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- Mission & Vision Section -->
<section class="about-section">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="analysis-card">
          <h3><i class="fas fa-bullseye me-2"></i>Our Mission</h3>
          <p>To provide accessible, reliable, and high-quality pharmaceutical services to our community, ensuring that every customer receives the care and attention they deserve.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="analysis-card">
          <h3><i class="fas fa-eye me-2"></i>Our Vision</h3>
          <p>To become the leading online pharmacy platform, known for innovation, customer satisfaction, and commitment to healthcare excellence.</p>
        </div>
      </div>
    </div>
  </div>
</section>

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