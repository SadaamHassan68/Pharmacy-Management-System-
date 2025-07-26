# Pharmacy Management System (PHP)

This is a starter template for an Online Pharmacy Management System with both Admin and Customer modules.

## Features
- Admin Dashboard (manage staff, drugs, orders, prescriptions, complaints, analytics)
- Customer Website (browse medicines, cart, order, upload prescription, complaints)
- Secure authentication for both admin and customers
- Responsive UI with Bootstrap

## Folder Structure
```
pharmacy-ms/
├── admin/
│   ├── dashboard.php
│   ├── login.php
│   ├── manage_staff.php
│   ├── add_drug.php
│   ├── manage_drug.php
│   ├── manage_orders.php
│   ├── track_orders.php
│   ├── manage_prescriptions.php
│   ├── handle_complaints.php
│   ├── analytics.php
│   └── includes/
│       ├── db.php
│       ├── header.php
│       ├── footer.php
│       └── session.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── index.php
├── login.php
├── register.php
├── medicines.php
├── medicine_details.php
├── cart.php
├── order.php
├── order_history.php
├── upload_prescription.php
├── complaint.php
└── includes/
    ├── db.php
    └── session.php
```

## Setup
1. Import the provided SQL schema into your MySQL database.
2. Update `includes/db.php` with your database credentials.
3. Access the admin panel via `/admin/login.php` and the customer site via `/login.php`.

---
This template uses Bootstrap for a modern, responsive UI. 