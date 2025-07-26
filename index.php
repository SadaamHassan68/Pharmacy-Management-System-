<?php
include 'includes/db.php';
session_start();
$featured_drugs = $conn->query("SELECT * FROM drugs WHERE quantity > 0 ORDER BY id DESC LIMIT 6");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy MS - Your Trusted Online Pharmacy</title>
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
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e3c72 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 60px;
        }
        
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .hero-section .lead {
            font-size: 1.25rem;
            margin-bottom: 30px;
        }
        
        .btn-hero {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .medicine-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .medicine-card:hover {
            transform: translateY(-5px);
        }
        
        .medicine-card .card-body {
            padding: 25px;
        }
        
        .medicine-card .card-title {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .price-tag {
            background: var(--secondary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background: #1e3c72;
            transform: translateY(-2px);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            color: var(--dark-text);
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: #666;
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

<!-- Hero Section -->
<section class="hero-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1>Your Health, Our Priority</h1>
        <p class="lead">Discover quality medicines and healthcare products delivered right to your doorstep. Trust our licensed pharmacy for all your medical needs.</p>
        <div class="d-flex gap-3">
          <a href="medicines.php" class="btn btn-light btn-hero">
            <i class="fas fa-pills me-2"></i>Browse Medicines
          </a>
          <?php if(!isset($_SESSION['customer_id'])): ?>
            <a href="register.php" class="btn btn-outline-light btn-hero">
              <i class="fas fa-user-plus me-2"></i>Get Started
            </a>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <i class="fas fa-heartbeat" style="font-size: 8rem; opacity: 0.3;"></i>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="py-5" style="background: var(--light-bg);">
  <div class="container">
    <div class="section-title">
      <h2>Why Choose Us?</h2>
      <p>We provide the best pharmacy services with quality and care</p>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-shipping-fast"></i>
          </div>
          <h4>Fast Delivery</h4>
          <p>Get your medicines delivered within 24 hours to your doorstep</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-certificate"></i>
          </div>
          <h4>Licensed Pharmacy</h4>
          <p>All our medicines are authentic and from licensed manufacturers</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-headset"></i>
          </div>
          <h4>24/7 Support</h4>
          <p>Our customer support team is available round the clock to help you</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Medicines -->
<section class="py-5">
  <div class="container">
    <div class="section-title">
      <h2>Featured Medicines</h2>
      <p>Browse our most popular and trusted medicines</p>
    </div>
    <div class="row">
      <?php while($drug = $featured_drugs->fetch_assoc()): ?>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="medicine-card">
          <div class="card-body">
            <div class="price-tag">$<?php echo $drug['price']; ?></div>
            <h5 class="card-title"><?php echo htmlspecialchars($drug['name']); ?></h5>
            <h6 class="card-subtitle mb-3 text-muted"><?php echo htmlspecialchars($drug['brand']); ?></h6>
            <div class="mb-3">
              <small class="text-muted">
                <i class="fas fa-capsules me-1"></i><?php echo htmlspecialchars($drug['dosage']); ?><br>
                <i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($drug['category']); ?><br>
                <i class="fas fa-box me-1"></i><?php echo $drug['quantity']; ?> units available
              </small>
            </div>
            <?php if(isset($_SESSION['customer_id'])): ?>
              <a href="medicine_details.php?id=<?php echo $drug['id']; ?>" class="btn btn-primary w-100">
                <i class="fas fa-eye me-2"></i>View Details
              </a>
            <?php else: ?>
              <a href="login.php" class="btn btn-primary w-100">
                <i class="fas fa-sign-in-alt me-2"></i>Login to Order
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    <div class="text-center mt-4">
      <a href="medicines.php" class="btn btn-outline-primary btn-lg">
        <i class="fas fa-pills me-2"></i>View All Medicines
      </a>
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