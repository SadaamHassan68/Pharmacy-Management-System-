<?php
include 'includes/session.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $stmt = $conn->prepare("INSERT INTO complaints (customer_id, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $_SESSION['customer_id'], $subject, $message);
    
    if ($stmt->execute()) {
        $success = "Complaint submitted successfully! We'll get back to you soon.";
    } else {
        $error = "Error submitting complaint. Please try again.";
    }
}

$complaints = $conn->query("SELECT * FROM complaints WHERE customer_id = " . $_SESSION['customer_id'] . " ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - Pharmacy MS</title>
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
        .complaint-card { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 30px; }
        .form-control { border-radius: 10px; padding: 12px 15px; border: 2px solid #e9ecef; }
        .form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25); }
        .btn-primary { background: var(--primary-color); border: none; border-radius: 10px; padding: 12px 30px; font-weight: 600; }
        .btn-primary:hover { background: #1e3c72; }
        .badge-status { font-size: 0.9rem; padding: 0.4em 0.8em; border-radius: 15px; }
        .complaint-item { background: #f8f9fa; border-radius: 10px; padding: 20px; margin-bottom: 15px; border-left: 4px solid var(--primary-color); }
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
        <li class="nav-item"><a class="nav-link active" href="complaint.php"><i class="fas fa-comment me-1"></i>Complaints</a></li>
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
    <h1><i class="fas fa-comment me-3"></i>Submit Complaint</h1>
  </div>
</section>

<div class="container mb-5">
  <!-- Submit New Complaint -->
  <div class="complaint-card">
    <h3 class="mb-4"><i class="fas fa-plus-circle me-2"></i>Submit New Complaint</h3>
    
    <?php if (isset($success)): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    
    <form method="POST">
      <div class="mb-4">
        <label for="subject" class="form-label"><i class="fas fa-tag me-2"></i>Subject</label>
        <input type="text" class="form-control" id="subject" name="subject" required placeholder="Brief description of your complaint">
      </div>
      
      <div class="mb-4">
        <label for="message" class="form-label"><i class="fas fa-comment me-2"></i>Message</label>
        <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Please provide detailed information about your complaint..."></textarea>
      </div>
      
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-paper-plane me-2"></i>Submit Complaint
        </button>
      </div>
    </form>
  </div>

  <!-- Complaint History -->
  <div class="complaint-card">
    <h3 class="mb-4"><i class="fas fa-history me-2"></i>Complaint History</h3>
    
    <?php if ($complaints->num_rows == 0): ?>
      <div class="text-center py-4">
        <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
        <h5 class="text-muted">No complaints submitted yet</h5>
        <p class="text-muted">Your submitted complaints will appear here</p>
      </div>
    <?php else: ?>
      <?php while($complaint = $complaints->fetch_assoc()): ?>
        <div class="complaint-item">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h6 class="mb-2"><i class="fas fa-tag me-2"></i><?php echo htmlspecialchars($complaint['subject']); ?></h6>
              <p class="mb-2 text-muted"><?php echo htmlspecialchars($complaint['message']); ?></p>
              <small class="text-muted">
                <i class="fas fa-calendar me-1"></i>Submitted on <?php echo date('M d, Y H:i', strtotime($complaint['created_at'])); ?>
              </small>
            </div>
            <div class="col-md-4 text-md-end">
              <span class="badge badge-status bg-<?php 
                echo $complaint['status'] == 'Pending' ? 'warning' : 
                    ($complaint['status'] == 'In Progress' ? 'info' : 
                    ($complaint['status'] == 'Resolved' ? 'success' : 'secondary')); 
              ?>">
                <i class="fas fa-<?php 
                  echo $complaint['status'] == 'Pending' ? 'clock' : 
                      ($complaint['status'] == 'In Progress' ? 'cog' : 
                      ($complaint['status'] == 'Resolved' ? 'check' : 'times')); 
                ?> me-1"></i>
                <?php echo $complaint['status']; ?>
              </span>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
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