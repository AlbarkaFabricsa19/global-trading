<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
?>
<style>
.header .dropdown-menu {
    max-height: 70vh !important;
    overflow-y: auto !important;
    overscroll-behavior: contain;
    -webkit-overflow-scrolling: touch;
    touch-action: pan-y;
}
.header .dropdown-menu::-webkit-scrollbar {
    width: 6px;
}
.header .dropdown-menu::-webkit-scrollbar-thumb {
    background: #00607a;
    border-radius: 4px;
}
.header .dropdown-menu::-webkit-scrollbar-track {
    background: #f1f5f9;
}
</style>
<header class="header header-transparent header-4">
    <div class="header-wrapper">
        <div class="sticky-height"></div>
        <!-- Navigation Menu Start -->
        <div class="header-nav-wrapper header-sticky">
            <nav class="navbar navbar-expand-xl">
                <div class="container-fluid mx-lg-5">
                    <a href="index.php" class="navbar-brand">
                        <img src="images/logo-w.png" alt="Global Trading Logo" class="img-fluid">
                    </a>
                    <button class="navbar-toggler offcanvas-nav-btn text-white" type="button">
                        Menu <svg xmlns="http://www.w3.org/2000/svg" width="14" height="12" fill="none"
                            viewBox="0 0 14 12">
                            <path fill="#fff"
                                d="M0 .75Q.063.063.75 0h12.5q.687.063.75.75-.063.687-.75.75H.75Q.063 1.437 0 .75m0 5Q.063 5.063.75 5h12.5q.687.063.75.75-.063.687-.75.75H.75Q.063 6.437 0 5.75m13.25 5.75H.75q-.687-.063-.75-.75.063-.687.75-.75h12.5q.687.063.75.75-.063.687-.75.75" />
                        </svg>
                    </button>
                    <div class="nav-cta d-none d-md-flex order-lg-3 gap-3">
                        <div class="d-flex call-cta gap-3 align-items-center">
                            <span class="icon rounded-circle text-white"><i class="fa fa-phone"></i></span>
                            <div class="call-inner">
                                <small class="text-white">Have Any Questions?</small>
                                <a class="text-white h6 d-block mb-0" href="tel:+923004612749">+92 300-4612749</a>
                            </div>
                        </div>
                    </div>
                    <div class="offcanvas offcanvas-start offcanvas-nav" data-lenis-prevent>
                        <div class="offcanvas-header">
                            <a href="index.php" class="text-inverse"><img src="images/logo.svg" alt="Global Trading Logo"></a>
                            <button type="button" class="btn-close bg-primary" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body pt-0 align-items-center justify-content-between" data-lenis-prevent>
                            <ul class="navbar-nav mx-auto align-items-lg-center">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="about.php">About Us</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="products.php" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">Products</a>
                                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 py-2" data-lenis-prevent data-lenis-prevent-wheel>
                                        <li><a class="dropdown-item py-2 px-3 fw-bold text-primary" href="products.php"><i class="fa fa-th-large me-2"></i>All Products Catalog</a></li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li class="dropdown-header text-uppercase fw-bold px-3 pt-2 text-primary" style="font-size:0.75rem; letter-spacing:0.5px;">1. Stitching Accessories</li>
                                        <li><a class="dropdown-item py-2 px-3" href="products.php?category=tapes"><i class="fa fa-angle-right me-2 text-primary"></i>Tapes (Masking, PVC, Dyed)</a></li>
                                        <li><a class="dropdown-item py-2 px-3" href="products.php?category=elastic"><i class="fa fa-angle-right me-2 text-primary"></i>Elastic (Needleloom, Plain)</a></li>
                                        <li><a class="dropdown-item py-2 px-3" href="products.php?category=buttons"><i class="fa fa-angle-right me-2 text-primary"></i>Buttons (Sea Shell, Plastic, Tich)</a></li>
                                        <li><a class="dropdown-item py-2 px-3" href="products.php?category=threads-cords"><i class="fa fa-angle-right me-2 text-primary"></i>Threads & Cords (Sateen & Tag Dori)</a></li>
                                        <li><a class="dropdown-item py-2 px-3" href="products.php?category=raw-materials"><i class="fa fa-angle-right me-2 text-primary"></i>High-Quality Raw Materials</a></li>
                                        <li><hr class="dropdown-divider my-2"></li>
                                        <li class="dropdown-header text-uppercase fw-bold px-3 pt-2 text-primary" style="font-size:0.75rem; letter-spacing:0.5px;">2. Mechanical & Electrical</li>
                                        <li><a class="dropdown-item py-2 px-3" href="products.php?category=pipes"><i class="fa fa-angle-right me-2 text-primary"></i>Pipes (PVC, GI, Steel, HDPE, Flexible)</a></li>
                                        <li><a class="dropdown-item py-2 px-3" href="products.php?category=sheets"><i class="fa fa-angle-right me-2 text-primary"></i>Sheets (Steel, Aluminum, Galvanized)</a></li>
                                        <li><a class="dropdown-item py-2 px-3" href="products.php?category=angles"><i class="fa fa-angle-right me-2 text-primary"></i>Angles (Mild & Stainless Steel)</a></li>
                                        <li><a class="dropdown-item py-2 px-3" href="products.php?category=channels"><i class="fa fa-angle-right me-2 text-primary"></i>Channels (C / U Channels)</a></li>
                                        <li><a class="dropdown-item py-2 px-3" href="products.php?category=additional-fittings"><i class="fa fa-angle-right me-2 text-primary"></i>Electrical & Pipe Fittings</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="contact.php">Contact Us</a>
                                </li>
                                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                                    <li class="nav-item ms-lg-2">
                                        <a class="btn btn-sm btn-primary rounded-pill px-3" href="admin/index.php"><i class="fa fa-cog me-1"></i> Admin Panel</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>