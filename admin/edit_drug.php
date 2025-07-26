<?php
include 'includes/session.php';
include 'includes/db.php';
include 'includes/header.php';

$id = $_GET['id'] ?? 0;
$drug = $conn->query("SELECT * FROM drugs WHERE id = $id")->fetch_assoc();

if(!$drug) {
    header("Location: manage_drug.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $dosage = $_POST['dosage'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $expiry_date = $_POST['expiry_date'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    
    $stmt = $conn->prepare("UPDATE drugs SET name=?, brand=?, dosage=?, quantity=?, price=?, expiry_date=?, category=?, description=? WHERE id=?");
    $stmt->bind_param("sssidsis", $name, $brand, $dosage, $quantity, $price, $expiry_date, $category, $description, $id);
    
    if ($stmt->execute()) {
        $success = "Drug updated successfully!";
        $drug = $conn->query("SELECT * FROM drugs WHERE id = $id")->fetch_assoc();
    } else {
        $error = "Error updating drug!";
    }
}
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit me-2"></i>Edit Drug</h2>
        <a href="manage_drug.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Drugs
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
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
            
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Drug Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($drug['name']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" value="<?php echo htmlspecialchars($drug['brand']); ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="dosage" class="form-label">Dosage</label>
                        <input type="text" class="form-control" id="dosage" name="dosage" value="<?php echo htmlspecialchars($drug['dosage']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $drug['quantity']; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $drug['price']; ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?php echo $drug['expiry_date']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Pain Relief" <?php echo $drug['category'] == 'Pain Relief' ? 'selected' : ''; ?>>Pain Relief</option>
                            <option value="Antibiotics" <?php echo $drug['category'] == 'Antibiotics' ? 'selected' : ''; ?>>Antibiotics</option>
                            <option value="Gastrointestinal" <?php echo $drug['category'] == 'Gastrointestinal' ? 'selected' : ''; ?>>Gastrointestinal</option>
                            <option value="Allergy" <?php echo $drug['category'] == 'Allergy' ? 'selected' : ''; ?>>Allergy</option>
                            <option value="Diabetes" <?php echo $drug['category'] == 'Diabetes' ? 'selected' : ''; ?>>Diabetes</option>
                            <option value="Cardiovascular" <?php echo $drug['category'] == 'Cardiovascular' ? 'selected' : ''; ?>>Cardiovascular</option>
                            <option value="Respiratory" <?php echo $drug['category'] == 'Respiratory' ? 'selected' : ''; ?>>Respiratory</option>
                            <option value="Other" <?php echo $drug['category'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($drug['description']); ?></textarea>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Drug
                    </button>
                    <a href="manage_drug.php" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 