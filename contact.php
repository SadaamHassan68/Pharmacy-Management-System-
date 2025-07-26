<?php
session_start();
include 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Pharmacy MS</title>
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
        
        .contact-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            height: 100%;
        }
        
        .contact-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
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
        
        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
    <h1><i class="fas fa-envelope me-3"></i>Contact Us</h1>
    <p class="lead">Get in touch with us for any questions or support</p>
  </div>
</section>

<!-- Contact Information -->
<section class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="contact-card text-center">
          <div class="contact-icon">
            <i class="fas fa-phone"></i>
          </div>
          <h5>Phone</h5>
          <p class="mb-2">+1 234 567 8900</p>
          <p class="text-muted">Monday - Friday: 8:00 AM - 8:00 PM</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="contact-card text-center">
          <div class="contact-icon">
            <i class="fas fa-envelope"></i>
          </div>
          <h5>Email</h5>
          <p class="mb-2">info@pharmacyms.com</p>
          <p class="text-muted">We'll respond within 24 hours</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="contact-card text-center">
          <div class="contact-icon">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <h5>Address</h5>
          <p class="mb-2">123 Pharmacy Street</p>
          <p class="text-muted">City, State 12345</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact Form and Map -->
<section class="py-5" style="background: var(--light-bg);">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 mb-4">
        <div class="contact-card">
          <h3 class="mb-4"><i class="fas fa-paper-plane me-2"></i>Send us a Message</h3>
          <form>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="tel" class="form-control" id="phone">
            </div>
            <div class="mb-3">
              <label for="subject" class="form-label">Subject</label>
              <select class="form-select" id="subject" required>
                <option value="">Choose a subject</option>
                <option value="general">General Inquiry</option>
                <option value="order">Order Question</option>
                <option value="prescription">Prescription Question</option>
                <option value="complaint">Complaint</option>
                <option value="feedback">Feedback</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="message" class="form-label">Message</label>
              <textarea class="form-control" id="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-paper-plane me-2"></i>Send Message
            </button>
          </form>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="map-container">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.2219901290355!2d-74.00369368400567!3d40.71312937933185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a23e28c1191%3A0x49f75d3281df052a!2s150%20Park%20Row%2C%20New%20York%2C%20NY%2010007%2C%20USA!5e0!3m2!1sen!2s!4v1640995200000!5m2!1sen!2s" 
            width="100%" 
            height="400" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
          </iframe>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Business Hours -->
<section class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="contact-card">
          <h3 class="text-center mb-4"><i class="fas fa-clock me-2"></i>Business Hours</h3>
          <div class="row">
            <div class="col-md-6">
              <h5>Online Store</h5>
              <ul class="list-unstyled">
                <li><strong>Monday - Friday:</strong> 24/7</li>
                <li><strong>Saturday:</strong> 24/7</li>
                <li><strong>Sunday:</strong> 24/7</li>
              </ul>
            </div>
            <div class="col-md-6">
              <h5>Customer Support</h5>
              <ul class="list-unstyled">
                <li><strong>Monday - Friday:</strong> 8:00 AM - 8:00 PM</li>
                <li><strong>Saturday:</strong> 9:00 AM - 6:00 PM</li>
                <li><strong>Sunday:</strong> 10:00 AM - 4:00 PM</li>
              </ul>
            </div>
          </div>
          <div class="text-center mt-4">
            <p class="text-muted">For urgent matters outside business hours, please email us and we'll respond as soon as possible.</p>
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