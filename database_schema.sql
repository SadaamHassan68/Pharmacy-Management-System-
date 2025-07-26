-- Pharmacy Management System Database Schema
-- Run this in phpMyAdmin or MySQL CLI to create the database and tables

-- Create database
CREATE DATABASE IF NOT EXISTS pharmacy_ms;
USE pharmacy_ms;

-- Admin/Staff Table
CREATE TABLE staff (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  phone VARCHAR(20),
  role VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Customers Table
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  phone VARCHAR(20),
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Drugs Table
CREATE TABLE drugs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  brand VARCHAR(100) NOT NULL,
  dosage VARCHAR(50) NOT NULL,
  quantity INT NOT NULL DEFAULT 0,
  price DECIMAL(10,2) NOT NULL,
  expiry_date DATE NOT NULL,
  category VARCHAR(50) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(20) DEFAULT 'Pending',
  total_amount DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- Order Items Table
CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  drug_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (drug_id) REFERENCES drugs(id)
);

-- Prescriptions Table
CREATE TABLE prescriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  approved_status VARCHAR(20) DEFAULT 'Pending',
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- Complaints Table
CREATE TABLE complaints (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  status VARCHAR(20) DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- Insert sample admin user (password: admin123)
INSERT INTO staff (name, email, phone, role, password) VALUES 
('Admin User', 'admin@pharmacy.com', '1234567890', 'Admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample drugs
INSERT INTO drugs (name, brand, dosage, quantity, price, expiry_date, category, description) VALUES 
('Paracetamol', 'Tylenol', '500mg', 100, 5.99, '2025-12-31', 'Pain Relief', 'For fever and pain relief'),
('Ibuprofen', 'Advil', '400mg', 75, 7.50, '2025-10-31', 'Pain Relief', 'Anti-inflammatory pain reliever'),
('Amoxicillin', 'Amoxil', '250mg', 50, 12.99, '2024-06-30', 'Antibiotics', 'Broad-spectrum antibiotic'),
('Omeprazole', 'Prilosec', '20mg', 60, 15.75, '2025-08-31', 'Gastrointestinal', 'For acid reflux and ulcers'),
('Cetirizine', 'Zyrtec', '10mg', 80, 8.25, '2025-11-30', 'Allergy', 'For seasonal allergies'),
('Metformin', 'Glucophage', '500mg', 40, 18.50, '2025-09-30', 'Diabetes', 'For type 2 diabetes management');

-- Insert sample customer (password: customer123)
INSERT INTO customers (name, email, phone, password) VALUES 
('John Doe', 'john@example.com', '9876543210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); 