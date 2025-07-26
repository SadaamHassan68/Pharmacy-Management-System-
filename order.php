<?php
include 'includes/session.php';
include 'includes/db.php';

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
if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO orders (customer_id, total_amount) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $_SESSION['customer_id'], $total);
        $stmt->execute();
        $order_id = $conn->insert_id;
        foreach ($cart_items as $item) {
            $sql = "INSERT INTO order_items (order_id, drug_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiid", $order_id, $item['drug']['id'], $item['quantity'], $item['drug']['price']);
            $stmt->execute();
            $new_quantity = $item['drug']['quantity'] - $item['quantity'];
            $conn->query("UPDATE drugs SET quantity = $new_quantity WHERE id = " . $item['drug']['id']);
        }
        $conn->commit();
        unset($_SESSION['cart']);
        $success = "Order placed successfully! Order ID: #$order_id";
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Error placing order. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - Pharmacy MS</title>
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
        .order-summary-card { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 30px; }
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
    <h1><i class="fas fa-credit-card me-3"></i>Place Order</h1>
  </div>
</section>

<div class="container mb-5">
  <?php if(isset($success)): ?>
    <div class="alert alert-success">
      <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
      <br><a href="order_history.php" class="btn btn-primary mt-2"><i class="fas fa-history me-2"></i>View My Orders</a>
    </div>
  <?php else: ?>
    <?php if(isset($error)): ?>
      <div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?></div>
    <?php endif; ?>
    <div class="row">
      <div class="col-md-8">
        <div class="order-summary-card mb-4">
          <h5 class="mb-3"><i class="fas fa-list me-2"></i>Order Summary</h5>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Medicine</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($cart_items as $item): ?>
                <tr>
                  <td><?php echo htmlspecialchars($item['drug']['name']); ?></td>
                  <td><?php echo $item['quantity']; ?></td>
                  <td>$<?php echo $item['drug']['price']; ?></td>
                  <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3" class="text-end">Total:</th>
                  <th>$<?php echo number_format($total, 2); ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="order-summary-card">
          <h5 class="mb-3"><i class="fas fa-truck me-2"></i>Delivery Information</h5>
          <form method="post">
            <div class="mb-3">
              <label for="address" class="form-label">Delivery Address</label>
              <textarea name="address" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone Number</label>
              <input type="tel" name="phone" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-credit-card me-2"></i>Confirm Order</button>
          </form>
        </div>
      </div>
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