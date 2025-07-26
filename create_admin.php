<?php
// Terminal-style Admin Creation Script
// Run this from command line: php create_admin.php

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from command line!\n");
}

echo "==========================================\n";
echo "    PHARMACY MS - ADMIN CREATION TOOL\n";
echo "==========================================\n\n";

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "pharmacy_ms";

try {
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error . "\n");
    }
    echo "✅ Database connected successfully!\n\n";
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage() . "\n");
}

// Function to get user input
function getInput($prompt) {
    echo $prompt;
    $handle = fopen("php://stdin", "r");
    $input = trim(fgets($handle));
    fclose($handle);
    return $input;
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate password strength
function validatePassword($password) {
    if (strlen($password) < 6) {
        return false;
    }
    return true;
}

// Function to hide password input
function getPassword($prompt) {
    echo $prompt;
    system('stty -echo');
    $handle = fopen("php://stdin", "r");
    $password = trim(fgets($handle));
    system('stty echo');
    fclose($handle);
    echo "\n";
    return $password;
}

// Main admin creation process
echo "Please enter admin details:\n";
echo "---------------------------\n";

// Get admin name
$name = getInput("Enter admin name: ");
if (empty($name)) {
    die("❌ Admin name cannot be empty!\n");
}

// Get admin email
$email = getInput("Enter admin email: ");
if (!validateEmail($email)) {
    die("❌ Invalid email format!\n");
}

// Check if email already exists
$check_stmt = $conn->prepare("SELECT id FROM staff WHERE email = ?");
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$result = $check_stmt->get_result();
if ($result->num_rows > 0) {
    die("❌ Email already exists in database!\n");
}

// Get admin phone
$phone = getInput("Enter admin phone (optional): ");

// Get admin role
echo "Select admin role:\n";
echo "1. Admin (Full access)\n";
echo "2. Manager (Limited access)\n";
echo "3. Staff (Basic access)\n";
$role_choice = getInput("Enter role choice (1-3): ");

switch ($role_choice) {
    case '1':
        $role = 'Admin';
        break;
    case '2':
        $role = 'Manager';
        break;
    case '3':
        $role = 'Staff';
        break;
    default:
        $role = 'Staff';
}

// Get password
$password = getPassword("Enter password: ");
if (!validatePassword($password)) {
    die("❌ Password must be at least 6 characters long!\n");
}

$confirm_password = getPassword("Confirm password: ");
if ($password !== $confirm_password) {
    die("❌ Passwords do not match!\n");
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert admin into database
$insert_stmt = $conn->prepare("INSERT INTO staff (name, email, phone, role, password) VALUES (?, ?, ?, ?, ?)");
$insert_stmt->bind_param("sssss", $name, $email, $phone, $role, $hashed_password);

if ($insert_stmt->execute()) {
    echo "\n==========================================\n";
    echo "✅ ADMIN CREATED SUCCESSFULLY!\n";
    echo "==========================================\n";
    echo "Name: " . $name . "\n";
    echo "Email: " . $email . "\n";
    echo "Role: " . $role . "\n";
    echo "Phone: " . ($phone ?: 'Not provided') . "\n";
    echo "\nYou can now login to admin panel with:\n";
    echo "URL: http://localhost/pharmacy%20ms/admin/login.php\n";
    echo "Email: " . $email . "\n";
    echo "Password: [The password you entered]\n";
    echo "==========================================\n";
} else {
    echo "❌ Error creating admin: " . $conn->error . "\n";
}

$conn->close();
?> 