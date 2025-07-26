<?php
include 'includes/session.php';
include 'includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$drug = $conn->query("SELECT * FROM drugs WHERE id = $id AND quantity > 0")->fetch_assoc();

if (!$drug) {
    header("Location: medicines.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = (int)$_POST['quantity'];
    if ($quantity > 0 && $quantity <= $drug['quantity']) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] += $quantity;
        } else {
            $_SESSION['cart'][$id] = $quantity;
        }
        $success = "Added to cart successfully!";
    } else {
        $error = "Invalid quantity!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($drug['name']); ?> - Pharmacy MS</title>
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
        .medicine-details-card { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 40px; }
        .medicine-title { color: var(--primary-color); font-weight: bold; font-size: 2rem; }
        .price-tag { background: var(--secondary-color); color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold; display: inline-block; margin-bottom: 15px; }
        .btn-primary { background: var(--primary-color); border: none; border-radius: 25px; padding: 10px 25px; font-weight: 600; }
        .btn-primary:hover { background: #1e3c72; transform: translateY(-2px); }
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
        <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart me-1"></i>Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="order_history.php"><i class="fas fa-history me-1"></i>My Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="complaint.php"><i class="fas fa-comment me-1"></i>Complaints</a></li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Header -->
<section class="page-header">
  <div class="container">
    <h1><i class="fas fa-capsules me-3"></i>Medicine Details</h1>
  </div>
</section>

<div class="container mb-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="medicine-details-card">
        <div class="row">
          <div class="col-md-8">
            <div class="price-tag mb-2">$<?php echo $drug['price']; ?></div>
            <div class="medicine-title mb-2"><?php echo htmlspecialchars($drug['name']); ?></div>
            <h6 class="text-muted mb-3"><?php echo htmlspecialchars($drug['brand']); ?></h6>
            <ul class="list-unstyled mb-3">
              <li><i class="fas fa-capsules me-2"></i><strong>Dosage:</strong> <?php echo htmlspecialchars($drug['dosage']); ?></li>
              <li><i class="fas fa-tag me-2"></i><strong>Category:</strong> <?php echo htmlspecialchars($drug['category']); ?></li>
              <li><i class="fas fa-box me-2"></i><strong>Available:</strong> <?php echo $drug['quantity']; ?> units</li>
              <li><i class="fas fa-calendar me-2"></i><strong>Expiry:</strong> <?php echo $drug['expiry_date']; ?></li>
            </ul>
            <?php if($drug['description']): ?>
              <p class="mb-3"><strong>Description:</strong> <?php echo htmlspecialchars($drug['description']); ?></p>
            <?php endif; ?>
          </div>
          <div class="col-md-4">
            <div class="card p-3">
              <h6 class="mb-3">Add to Cart</h6>
              <?php if(isset($success)): ?>
                <div class="alert alert-success py-2"><?php echo $success; ?></div>
              <?php endif; ?>
              <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2"><?php echo $error; ?></div>
              <?php endif; ?>
              <form method="post">
                <div class="mb-3">
                  <label for="quantity" class="form-label">Quantity</label>
                  <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo $drug['quantity']; ?>" required>
                  <small class="text-muted">Available: <?php echo $drug['quantity']; ?> units</small>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-cart-plus me-2"></i>Add to Cart</button>
              </form>
              <div class="mt-3">
                <a href="cart.php" class="btn btn-outline-primary w-100"><i class="fas fa-shopping-cart me-2"></i>View Cart</a>
              </div>
            </div>
          </div>
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