<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Pharmacy MS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #4CAF50;
            --accent-color: #FF6B35;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --dark-bg: #1a1a2e;
            --sidebar-bg: #16213e;
            --sidebar-hover: #0f3460;
            --text-light: #ffffff;
            --text-muted: #b8b8b8;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--text-light);
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-header .logo {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .sidebar-header h4 {
            margin: 0;
            font-size: 1.1rem;
            color: var(--text-light);
        }
        
        .sidebar-header .subtitle {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        
        .sidebar.collapsed .sidebar-header h4,
        .sidebar.collapsed .sidebar-header .subtitle {
            display: none;
        }
        
        .sidebar-nav {
            padding: 20px 0;
        }
        
        .nav-item {
            margin-bottom: 5px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            background: var(--sidebar-hover);
            color: var(--text-light);
            border-left-color: var(--secondary-color);
        }
        
        .nav-link.active {
            background: var(--secondary-color);
            color: var(--text-light);
            border-left-color: var(--text-light);
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }
        
        .nav-link span {
            font-weight: 500;
        }
        
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 15px 10px;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .top-bar-left {
            display: flex;
            align-items: center;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-right: 20px;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .sidebar-toggle:hover {
            background: #f8f9fa;
            color: var(--secondary-color);
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-bg);
            margin: 0;
        }
        
        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .admin-avatar {
            width: 40px;
            height: 40px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .admin-details {
            display: flex;
            flex-direction: column;
        }
        
        .admin-name {
            font-weight: 600;
            color: var(--dark-bg);
            font-size: 0.9rem;
        }
        
        .admin-role {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        
        .logout-btn {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #e55a2b;
            color: white;
        }
        
        /* Content Area */
        .content-area {
            padding: 30px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
        }
        
        /* Tooltip for collapsed sidebar */
        .sidebar.collapsed .nav-link {
            position: relative;
        }
        
        .sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: var(--dark-bg);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            white-space: nowrap;
            z-index: 1001;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-pills"></i>
            </div>
            <h4>Pharmacy MS</h4>
            <div class="subtitle">Admin Panel</div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" data-title="Dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="manage_staff.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_staff.php' ? 'active' : ''; ?>" data-title="Manage Staff">
                    <i class="fas fa-users"></i>
                    <span>Manage Staff</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="add_staff.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_staff.php' ? 'active' : ''; ?>" data-title="Add Staff">
                    <i class="fas fa-user-plus"></i>
                    <span>Add Staff</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="manage_drug.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_drug.php' ? 'active' : ''; ?>" data-title="Manage Drugs">
                    <i class="fas fa-pills"></i>
                    <span>Manage Drugs</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="add_drug.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_drug.php' ? 'active' : ''; ?>" data-title="Add Drug">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Drug</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="manage_orders.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_orders.php' ? 'active' : ''; ?>" data-title="Manage Orders">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Manage Orders</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="../index.php" class="nav-link" data-title="View Site">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Site</span>
                </a>
            </div>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?></h1>
            </div>
            
            <div class="top-bar-right">
                <div class="admin-info">
                    <div class="admin-avatar">
                        <?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?>
                    </div>
                    <div class="admin-details">
                        <div class="admin-name"><?php echo $_SESSION['admin_name'] ?? 'Admin'; ?></div>
                        <div class="admin-role"><?php echo $_SESSION['admin_role'] ?? 'Administrator'; ?></div>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area"> 