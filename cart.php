<?php
include 'includes/session.php';
include 'includes/db.php';

// Handle cart actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($action == 'remove' && isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    } elseif ($action == 'update' && isset($_POST['quantity'][$id])) {
        $quantity = (int)$_POST['quantity'][$id];
        if ($quantity > 0) {
            $_SESSION['cart'][$id] = $quantity;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
}

$cart_items = [];
$total = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $drug = $conn->query("SELECT * FROM drugs WHERE id = $id")->fetch_assoc();
        if ($drug) {
            $cart_items[] = [
                'drug' => $drug,
                'quantity' => $quantity,
                'subtotal' => $drug['price'] * $quantity
            ];
            $total += $drug['price'] * $quantity;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Pharmacy MS</title>
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
        .cart-table { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 30px; }
        .btn-primary { background: var(--primary-color); border: none; border-radius: 25px; padding: 10px 25px; font-weight: 600; }
        .btn-primary:hover { background: #1e3c72; transform: translateY(-2px); }
        .btn-outline-primary { border-color: var(--primary-color); color: var(--primary-color); border-radius: 25px; padding: 10px 25px; font-weight: 600; }
        .btn-outline-primary:hover { background: var(--primary-color); border-color: var(--primary-color); color: white; }
        .footer { background: var(--dark-text); color: white; padding: 50px 0 20px; margin-top: 80px; }
        .footer h5 { color: var(--secondary-color); margin-bottom: 20px; }
        .footer a { color: #bdc3c7; text-decoration: none; }
        .footer a:hover { color: var(--secondary-color); }
        .social-icons a { display: inline-block; width: 40px; height: 40px; background: var(--primary-color); color: white; text-align: center; line-height: 40px; border-radius: 50%; margin-right: 10px; transition: background 0.3s ease; }
        .social-icons a:hover { background: var(--secondary-color); }
        .empty-cart { text-align: center; padding: 60px 20px; color: #666; }
        .empty-cart i { font-size: 4rem; color: #ddd; margin-bottom: 20px; }
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
        <li class="nav-item"><a class="nav-link active" href="cart.php"><i class="fas fa-shopping-cart me-1"></i>Cart</a></li>
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
    <h1><i class="fas fa-shopping-cart me-3"></i>Shopping Cart</h1>
  </div>
</section>

<div class="container mb-5">
  <?php if (empty($cart_items)): ?>
    <div class="empty-cart">
      <i class="fas fa-shopping-cart"></i>
      <h3>Your cart is empty</h3>
      <p>Looks like you haven't added any medicines yet.</p>
      <a href="medicines.php" class="btn btn-primary"><i class="fas fa-pills me-2"></i>Browse Medicines</a>
    </div>
  <?php else: ?>
    <form method="post" action="?action=update">
      <div class="cart-table mb-4">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Medicine</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cart_items as $item): ?>
              <tr>
                <td><strong><?php echo htmlspecialchars($item['drug']['name']); ?></strong></td>
                <td><?php echo htmlspecialchars($item['drug']['brand']); ?></td>
                <td>$<?php echo $item['drug']['price']; ?></td>
                <td>
                  <input type="number" name="quantity[<?php echo $item['drug']['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['drug']['quantity']; ?>" class="form-control" style="width: 80px;">
                </td>
                <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                <td>
                  <a href="?action=remove&id=<?php echo $item['drug']['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove this item?')"><i class="fas fa-trash"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
          <button type="submit" class="btn btn-outline-primary"><i class="fas fa-sync-alt me-2"></i>Update Cart</button>
          <a href="medicines.php" class="btn btn-outline-primary ms-2"><i class="fas fa-pills me-2"></i>Continue Shopping</a>
        </div>
        <div class="col-md-6 text-end">
          <h4>Total: <span class="text-success">$<?php echo number_format($total, 2); ?></span></h4>
          <a href="order.php" class="btn btn-primary btn-lg mt-2"><i class="fas fa-credit-card me-2"></i>Proceed to Checkout</a>
        </div>
      </div>
    </form>
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