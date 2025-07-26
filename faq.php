<?php
session_start();
include 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Pharmacy MS</title>
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
        
        .faq-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .faq-header {
            background: var(--light-bg);
            padding: 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .faq-header:hover {
            background: #e9ecef;
        }
        
        .faq-header h5 {
            margin: 0;
            color: var(--primary-color);
        }
        
        .faq-body {
            padding: 20px;
            border-top: 1px solid #dee2e6;
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
        
        .category-section {
            background: var(--light-bg);
            padding: 40px 0;
            margin-bottom: 40px;
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
    <h1><i class="fas fa-question-circle me-3"></i>Frequently Asked Questions</h1>
    <p class="lead">Find answers to common questions about our pharmacy services</p>
  </div>
</section>

<!-- General Questions -->
<section class="py-5">
  <div class="container">
    <h2 class="text-center mb-5"><i class="fas fa-info-circle me-2"></i>General Questions</h2>
    
    <div class="accordion" id="generalFAQ">
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq1">
          <h5><i class="fas fa-plus me-2"></i>How do I create an account?</h5>
        </div>
        <div id="faq1" class="collapse faq-body" data-bs-parent="#generalFAQ">
          <p>To create an account, click on the "Register" button in the top navigation. Fill in your personal information including name, email, phone number, and password. Once registered, you can log in and start ordering medicines.</p>
        </div>
      </div>
      
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq2">
          <h5><i class="fas fa-plus me-2"></i>Are your medicines authentic?</h5>
        </div>
        <div id="faq2" class="collapse faq-body" data-bs-parent="#generalFAQ">
          <p>Yes, all our medicines are authentic and sourced from licensed manufacturers. We maintain strict quality control measures and only stock medicines from approved suppliers to ensure your safety.</p>
        </div>
      </div>
      
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq3">
          <h5><i class="fas fa-plus me-2"></i>What payment methods do you accept?</h5>
        </div>
        <div id="faq3" class="collapse faq-body" data-bs-parent="#generalFAQ">
          <p>We accept various payment methods including credit/debit cards, digital wallets, and cash on delivery. All online payments are processed through secure payment gateways to ensure your financial information is protected.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Ordering Questions -->
<section class="category-section">
  <div class="container">
    <h2 class="text-center mb-5"><i class="fas fa-shopping-cart me-2"></i>Ordering & Delivery</h2>
    
    <div class="accordion" id="orderingFAQ">
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq4">
          <h5><i class="fas fa-plus me-2"></i>How long does delivery take?</h5>
        </div>
        <div id="faq4" class="collapse faq-body" data-bs-parent="#orderingFAQ">
          <p>Standard delivery takes 24-48 hours within the city and 3-5 days for out-of-city locations. Express delivery is available for urgent orders with delivery within 4-6 hours in select areas.</p>
        </div>
      </div>
      
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq5">
          <h5><i class="fas fa-plus me-2"></i>Can I track my order?</h5>
        </div>
        <div id="faq5" class="collapse faq-body" data-bs-parent="#orderingFAQ">
          <p>Yes, you can track your order through your account. Go to "My Orders" section and click on the order number to view real-time tracking information and delivery status.</p>
        </div>
      </div>
      
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq6">
          <h5><i class="fas fa-plus me-2"></i>What if my order is damaged or incorrect?</h5>
        </div>
        <div id="faq6" class="collapse faq-body" data-bs-parent="#orderingFAQ">
          <p>If you receive a damaged or incorrect order, please contact our customer support immediately. We offer a 100% replacement guarantee and will arrange for a replacement or refund as appropriate.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Prescription Questions -->
<section class="py-5">
  <div class="container">
    <h2 class="text-center mb-5"><i class="fas fa-file-medical me-2"></i>Prescriptions</h2>
    
    <div class="accordion" id="prescriptionFAQ">
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq7">
          <h5><i class="fas fa-plus me-2"></i>How do I upload a prescription?</h5>
        </div>
        <div id="faq7" class="collapse faq-body" data-bs-parent="#prescriptionFAQ">
          <p>To upload a prescription, go to the "Upload Prescription" page, click "Choose File" to select your prescription image or PDF, and click "Upload Prescription". We accept JPG, PNG, GIF, and PDF formats.</p>
        </div>
      </div>
      
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq8">
          <h5><i class="fas fa-plus me-2"></i>How long does prescription approval take?</h5>
        </div>
        <div id="faq8" class="collapse faq-body" data-bs-parent="#prescriptionFAQ">
          <p>Prescription approval typically takes 2-4 hours during business hours. You'll receive an email notification once your prescription is approved or if any clarification is needed.</p>
        </div>
      </div>
      
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq9">
          <h5><i class="fas fa-plus me-2"></i>Can I order prescription medicines without a prescription?</h5>
        </div>
        <div id="faq9" class="collapse faq-body" data-bs-parent="#prescriptionFAQ">
          <p>No, prescription medicines require a valid prescription from a licensed healthcare provider. This is a legal requirement to ensure your safety and proper medication use.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Account & Security -->
<section class="category-section">
  <div class="container">
    <h2 class="text-center mb-5"><i class="fas fa-user-shield me-2"></i>Account & Security</h2>
    
    <div class="accordion" id="securityFAQ">
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq10">
          <h5><i class="fas fa-plus me-2"></i>How do I reset my password?</h5>
        </div>
        <div id="faq10" class="collapse faq-body" data-bs-parent="#securityFAQ">
          <p>To reset your password, go to the login page and click "Forgot Password". Enter your registered email address and follow the instructions sent to your email to create a new password.</p>
        </div>
      </div>
      
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq11">
          <h5><i class="fas fa-plus me-2"></i>Is my personal information secure?</h5>
        </div>
        <div id="faq11" class="collapse faq-body" data-bs-parent="#securityFAQ">
          <p>Yes, we use industry-standard encryption and security measures to protect your personal information. We never share your data with third parties without your consent.</p>
        </div>
      </div>
      
      <div class="faq-card">
        <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq12">
          <h5><i class="fas fa-plus me-2"></i>Can I update my account information?</h5>
        </div>
        <div id="faq12" class="collapse faq-body" data-bs-parent="#securityFAQ">
          <p>Yes, you can update your account information by going to your profile settings. You can modify your name, phone number, and address. Email changes require verification for security purposes.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact Support -->
<section class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto text-center">
        <div class="faq-card">
          <h3><i class="fas fa-headset me-2"></i>Still Have Questions?</h3>
          <p class="lead">If you couldn't find the answer you're looking for, our customer support team is here to help!</p>
          <div class="d-flex justify-content-center gap-3">
            <a href="contact.php" class="btn btn-primary">
              <i class="fas fa-envelope me-2"></i>Contact Us
            </a>
            <a href="complaint.php" class="btn btn-outline-primary">
              <i class="fas fa-comment me-2"></i>Submit Complaint
            </a>
          </div>
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