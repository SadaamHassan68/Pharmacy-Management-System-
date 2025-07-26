<?php
include 'includes/session.php';
include 'includes/db.php';
include 'includes/header.php';
// Fetch counts for dashboard
$medicines = $conn->query("SELECT COUNT(*) as total FROM drugs")->fetch_assoc()['total'] ?? 0;
$staff = $conn->query("SELECT COUNT(*) as total FROM staff")->fetch_assoc()['total'] ?? 0;
$orders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'] ?? 0;
$prescriptions = $conn->query("SELECT COUNT(*) as total FROM prescriptions")->fetch_assoc()['total'] ?? 0;
$complaints = $conn->query("SELECT COUNT(*) as total FROM complaints")->fetch_assoc()['total'] ?? 0;
?>
<h1 class="mb-4">Admin Dashboard</h1>
<div class="row g-4">
  <div class="col-md-4">
    <div class="card text-bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Total Medicines</h5>
        <p class="card-text display-6"><?php echo $medicines; ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Total Staff</h5>
        <p class="card-text display-6"><?php echo $staff; ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-info mb-3">
      <div class="card-body">
        <h5 class="card-title">Total Orders</h5>
        <p class="card-text display-6"><?php echo $orders; ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-warning mb-3">
      <div class="card-body">
        <h5 class="card-title">Prescriptions Uploaded</h5>
        <p class="card-text display-6"><?php echo $prescriptions; ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-danger mb-3">
      <div class="card-body">
        <h5 class="card-title">Customer Complaints</h5>
        <p class="card-text display-6"><?php echo $complaints; ?></p>
      </div>
    </div>
  </div>
</div>
<a href="add_drug.php" class="btn btn-primary me-2">Add Drug</a>
<a href="manage_orders.php" class="btn btn-secondary">View Orders</a>
<?php include 'includes/footer.php'; ?> 