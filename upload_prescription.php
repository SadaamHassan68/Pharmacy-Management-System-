<?php
include 'includes/session.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $upload_dir = 'uploads/prescriptions/';
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    if (isset($_FILES['prescription']) && $_FILES['prescription']['error'] == 0) {
        $file = $_FILES['prescription'];
        $file_name = time() . '_' . $file['name'];
        $file_path = $upload_dir . $file_name;
        
        // Check file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        if (in_array($file['type'], $allowed_types)) {
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $stmt = $conn->prepare("INSERT INTO prescriptions (customer_id, file_path) VALUES (?, ?)");
                $stmt->bind_param("is", $_SESSION['customer_id'], $file_path);
                
                if ($stmt->execute()) {
                    $success = "Prescription uploaded successfully! We'll review it and get back to you soon.";
                } else {
                    $error = "Error saving prescription. Please try again.";
                }
            } else {
                $error = "Error uploading file. Please try again.";
            }
        } else {
            $error = "Invalid file type. Please upload JPG, PNG, GIF, or PDF files only.";
        }
    } else {
        $error = "Please select a file to upload.";
    }
}

// Get user's prescriptions
$prescriptions = $conn->query("SELECT * FROM prescriptions WHERE customer_id = " . $_SESSION['customer_id'] . " ORDER BY uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Prescription - Pharmacy MS</title>
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
        
        .upload-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .upload-area {
            border: 2px dashed var(--primary-color);
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background: var(--light-bg);
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            border-color: var(--secondary-color);
            background: #e8f5e8;
        }
        
        .upload-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .prescription-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
    <h1><i class="fas fa-upload me-3"></i>Upload Prescription</h1>
    <p class="lead">Upload your prescription for review and approval</p>
  </div>
</section>

<!-- Upload Section -->
<section class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="upload-card">
          <?php if(isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
          
          <?php if(isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
          
          <h3 class="mb-4"><i class="fas fa-file-medical me-2"></i>Upload New Prescription</h3>
          
          <form method="POST" enctype="multipart/form-data">
            <div class="upload-area">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <h5>Choose your prescription file</h5>
              <p class="text-muted">Supported formats: JPG, PNG, GIF, PDF</p>
              <input type="file" name="prescription" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf" required>
            </div>
            
            <div class="mt-4">
              <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-upload me-2"></i>Upload Prescription
              </button>
            </div>
          </form>
          
          <div class="mt-5">
            <h4><i class="fas fa-info-circle me-2"></i>Important Information</h4>
            <ul class="list-unstyled">
              <li><i class="fas fa-check text-success me-2"></i>Ensure your prescription is clearly visible</li>
              <li><i class="fas fa-check text-success me-2"></i>Include all necessary details (medicine name, dosage, frequency)</li>
              <li><i class="fas fa-check text-success me-2"></i>Make sure the doctor's signature is visible</li>
              <li><i class="fas fa-check text-success me-2"></i>We'll review your prescription within 24 hours</li>
              <li><i class="fas fa-check text-success me-2"></i>You'll receive an email notification once approved</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Previous Prescriptions Section -->
<section class="py-5" style="background: var(--light-bg);">
  <div class="container">
    <h2 class="text-center mb-5"><i class="fas fa-history me-2"></i>Your Previous Prescriptions</h2>
    
    <?php if($prescriptions->num_rows > 0): ?>
      <div class="row">
        <?php while($prescription = $prescriptions->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="prescription-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <h6><i class="fas fa-file-medical me-2"></i>Prescription #<?php echo $prescription['id']; ?></h6>
                <small class="text-muted">Uploaded: <?php echo date('M d, Y', strtotime($prescription['uploaded_at'])); ?></small>
              </div>
              <span class="badge bg-<?php echo $prescription['approved_status'] == 'Approved' ? 'success' : ($prescription['approved_status'] == 'Rejected' ? 'danger' : 'warning'); ?>">
                <?php echo $prescription['approved_status']; ?>
              </span>
            </div>
            <div class="d-grid">
              <a href="<?php echo $prescription['file_path']; ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye me-2"></i>View File
              </a>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="text-center">
        <i class="fas fa-file-medical" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
        <h5>No prescriptions uploaded yet</h5>
        <p class="text-muted">Upload your first prescription above to get started.</p>
      </div>
    <?php endif; ?>
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