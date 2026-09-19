<?php
// admin/navbar.php - Shared Sidebar & Topbar Dashboard Navigation
require_once __DIR__ . '/auth.php';
requireAdmin();

$activePage = basename($_SERVER['PHP_SELF']);
$adminUser = $_SESSION['admin_username'] ?? 'Admin';
$initial = strtoupper(substr($adminUser, 0, 1));
?>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Left Sidebar Navigation -->
<aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand Logo -->
    <a href="index.php" class="sidebar-brand">
        <img src="../images/logo-w.png" alt="Global Trading Logo">
        <div>
            <small class="text-white-50" style="font-size: 0.72rem; letter-spacing: 0.5px;">ADMIN PANEL</small>
        </div>
    </a>

    <!-- Navigation Menu -->
    <div class="sidebar-menu-wrapper">
        <div class="sidebar-heading">Dashboard</div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="index.php" class="nav-link <?= $activePage == 'index.php' ? 'active' : '' ?>">
                    <i class="fa fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">Catalog Management</div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="products.php" class="nav-link <?= $activePage == 'products.php' ? 'active' : '' ?>">
                    <i class="fa fa-boxes"></i>
                    <span>All Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="add-product.php" class="nav-link <?= $activePage == 'add-product.php' ? 'active' : '' ?>">
                    <i class="fa fa-plus-circle"></i>
                    <span>Add Product</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="categories.php" class="nav-link <?= $activePage == 'categories.php' ? 'active' : '' ?>">
                    <i class="fa fa-tags"></i>
                    <span>Categories</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">Partners & Clients</div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="customers.php" class="nav-link <?= $activePage == 'customers.php' ? 'active' : '' ?>">
                    <i class="fa fa-handshake"></i>
                    <span>Trusted Customers</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">Quick Links</div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="../index.php" target="_blank" class="nav-link">
                    <i class="fa fa-external-link-alt text-primary"></i>
                    <span>View Live Website</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Sidebar Bottom User Card -->
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="d-flex align-items-center gap-2">
                <div class="sidebar-user-avatar"><?= $initial ?></div>
                <div style="line-height: 1.2;">
                    <strong class="d-block text-white small"><?= htmlspecialchars($adminUser) ?></strong>
                    <span class="text-white-50" style="font-size: 0.72rem;"><i class="fa fa-shield-alt text-warning me-1"></i>Administrator</span>
                </div>
            </div>
            <a href="logout.php" class="btn btn-outline-danger btn-sm p-2 rounded-circle" title="Logout">
                <i class="fa fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</aside>

<!-- Main Wrapper -->
<div class="admin-main">
    <!-- Topbar Header -->
    <header class="admin-topbar">
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="topbar-toggle-btn" onclick="toggleSidebar()">
                <i class="fa fa-bars"></i>
            </button>
            <h1 class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Admin Dashboard') ?></h1>
        </div>

        <div class="topbar-actions">
            <a href="../products.php" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 d-none d-md-inline-flex align-items-center">
                <i class="fa fa-external-link-alt me-2"></i> Preview Products
            </a>
            <div class="d-flex align-items-center gap-2 ps-2 border-start">
                <div class="sidebar-user-avatar" style="width: 34px; height: 34px; font-size: 0.85rem;"><?= $initial ?></div>
                <span class="fw-semibold small text-dark d-none d-sm-inline"><?= htmlspecialchars($adminUser) ?></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill ms-1 px-3">
                    <i class="fa fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <!-- Dashboard Body Area Starts -->
    <main class="admin-body">
