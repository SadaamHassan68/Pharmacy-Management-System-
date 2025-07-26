<?php
include 'includes/db.php';
session_start();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$where_clause = "WHERE quantity > 0";
if ($search) {
    $where_clause .= " AND (name LIKE '%$search%' OR brand LIKE '%$search%' OR category LIKE '%$search%')";
}
if ($category) {
    $where_clause .= " AND category = '$category'";
}

$drugs = $conn->query("SELECT * FROM drugs $where_clause ORDER BY name");
$categories = $conn->query("SELECT DISTINCT category FROM drugs WHERE quantity > 0 ORDER BY category");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Medicines - Pharmacy MS</title>
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
            padding: 60px 0;
            margin-bottom: 40px;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .search-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        
        .medicine-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
            border: none;
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
        
        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
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
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .no-results i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
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
        <li class="nav-item"><a class="nav-link active" href="medicines.php"><i class="fas fa-pills me-1"></i>Medicines</a></li>
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
        <h1><i class="fas fa-pills me-3"></i>Browse Medicines</h1>
        <p class="lead mb-0">Discover our wide range of quality medicines and healthcare products</p>
      </div>
      <div class="col-lg-4 text-center">
        <i class="fas fa-search" style="font-size: 4rem; opacity: 0.3;"></i>
      </div>
    </div>
  </div>
</section>

<div class="container">
  <!-- Search and Filter Section -->
  <div class="search-section">
    <div class="row">
      <div class="col-md-8">
        <form method="get" class="d-flex">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Search medicines by name, brand, or category..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-search me-2"></i>Search
            </button>
          </div>
        </form>
      </div>
      <div class="col-md-4">
        <form method="get">
          <?php if($search): ?>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
          <?php endif; ?>
          <select name="category" class="form-select" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php while($cat = $categories->fetch_assoc()): ?>
              <option value="<?php echo $cat['category']; ?>" <?php echo ($category == $cat['category']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cat['category']); ?>
              </option>
            <?php endwhile; ?>
          </select>
        </form>
      </div>
    </div>
  </div>

  <!-- Medicines Grid -->
  <div class="row">
    <?php 
    $medicine_count = 0;
    while($drug = $drugs->fetch_assoc()): 
      $medicine_count++;
    ?>
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
              <i class="fas fa-box me-1"></i><?php echo $drug['quantity']; ?> units available<br>
              <i class="fas fa-calendar me-1"></i>Expires: <?php echo date('M Y', strtotime($drug['expiry_date'])); ?>
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

  <!-- No Results Message -->
  <?php if($medicine_count == 0): ?>
  <div class="no-results">
    <i class="fas fa-search"></i>
    <h3>No medicines found</h3>
    <p>Try adjusting your search criteria or browse all categories</p>
    <a href="medicines.php" class="btn btn-outline-primary">
      <i class="fas fa-pills me-2"></i>View All Medicines
    </a>
  </div>
  <?php endif; ?>
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