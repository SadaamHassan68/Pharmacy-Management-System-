<?php
include 'includes/session.php';
include 'includes/db.php';
include 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $dosage = $_POST['dosage'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $expiry_date = $_POST['expiry_date'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $sql = "INSERT INTO drugs (name, brand, dosage, quantity, price, expiry_date, category, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissss", $name, $brand, $dosage, $quantity, $price, $expiry_date, $category, $description);
    if ($stmt->execute()) {
        $success = "Drug added successfully!";
    } else {
        $error = "Error adding drug.";
    }
}
?>
<h2>Add New Drug</h2>
<?php if(isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
  <div class="row mb-3">
    <div class="col">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col">
      <label class="form-label">Brand</label>
      <input type="text" name="brand" class="form-control" required>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col">
      <label class="form-label">Dosage</label>
      <input type="text" name="dosage" class="form-control" required>
    </div>
    <div class="col">
      <label class="form-label">Quantity</label>
      <input type="number" name="quantity" class="form-control" required>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col">
      <label class="form-label">Price</label>
      <input type="number" step="0.01" name="price" class="form-control" required>
    </div>
    <div class="col">
      <label class="form-label">Expiry Date</label>
      <input type="date" name="expiry_date" class="form-control" required>
    </div>
  </div>
  <div class="mb-3">
    <label class="form-label">Category</label>
    <input type="text" name="category" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="2"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Add Drug</button>
</form>
<?php include 'includes/footer.php'; ?> 