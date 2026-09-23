<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
   @session_start();
}
// Load DB & fetch trusted customers for dynamic section
require_once __DIR__ . '/config/db.php';
$pdo = getDBConnection();
$trustedCustomers = $pdo->query("SELECT * FROM trusted_customers WHERE status = 1 ORDER BY sort_order ASC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<!-- Header Links Start -->
<?php include 'include/headerLinks.php'; ?>
<!-- Header Links End -->

<body class="tv-magic-cursor">
   <div class="main-overlay"></div>
   <!-- Preloader -->
   <div id="preloader">
      <div class="preloader">
         <span></span>
         <span></span>
      </div>
   </div>

   <!-- Canvas Menu Start -->
   <div class="canvas-menu d-flex flex-column" data-lenis-prevent>
      <div class="d-flex justify-content-between w-100 mb-4">
         <!-- Logo Here -->
         <div class="logo">
            <img src="images/logo.svg" alt="Global Trading Logo">
         </div>
         <!-- Close Button -->
         <button type="button" class="canvas-close" aria-label="Close">
            <svg width="33" height="34" viewBox="0 0 37 38" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path d="M9.19141 9.80762L27.5762 28.1924" stroke="currentColor" stroke-width="1.5"
                  stroke-linecap="round" stroke-linejoin="round"></path>
               <path d="M9.19141 28.1924L27.5762 9.80761" stroke="currentColor" stroke-width="1.5"
                  stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
         </button>
      </div>
      <p>Global Trading provides high-quality stitching accessories, mechanical and electrical fittings with reliable supply and direct imports.</p>
      <!-- Vertical Menu Start-->
      <div class="mt-3">
         <h5>Quick Links</h5>
         <nav class="mt-4">
            <ul class="vertical-menu">
               <li><a href="index.php">Home Page</a></li>
               <li><a href="about.php">About & Leadership</a></li>
               <li><a href="products.php">Product Catalog</a></li>
               <li><a href="#trusted-clients">Our Trusted Clients</a></li>
               <li><a href="contact.php">Contact Us</a></li>
            </ul>
         </nav>
      </div>

      <!-- social icons -->
      <div class="social-share null mt-3">
         <a href="#"><i class="fab fa-facebook-f"></i></a>
         <a href="#"><i class="fab fa-x-twitter"></i></a>
         <a href="#"><i class="fab fa-instagram"></i></a>
         <a href="#"><i class="fab fa-linkedin-in"></i></a>
      </div>
      <a href="contact.php" class="btn btn-xs btn-primary mt-4"> Contact Us <i class="fa fa-arrow-right"></i><span></span></a>
   </div>
   <!-- Canvas Menu End -->

   <!-- Header Start -->
   <?php include 'include/header.php'; ?>
   <!-- Header End -->

   <!-- Hero Section Start -->
   <section class="hero-video">
      <img src="images/logo-txt.png" class="logo-txt anim-img moveXS" alt="Global Trading">
      <div class="hero-bg">
         <video autoplay="" muted="" loop="" id="hero-video">
            <source src="https://theme-village.com/profile/business-consulting.mp4" type="video/mp4">
         </video>
      </div>
      <div class="hero-grid-lines">
         <div class="line"></div>
         <div class="line"></div>
         <div class="line"></div>
         <div class="line"></div>
      </div>
      <div class="container-fluid px-lg-5">
         <div class="row">
            <div class="col-xl-8 col-lg-9">
               <div class="hero-content py-2">
                  <h1 class="hero-title text-white fw-bold text-uppercase display-4">WE PROVIDE THE BEST INDUSTRIAL & TRADING SOLUTIONS</h1>
                  <p class="lead text-white-50 mt-3 fs-5">Leading importer & supplier of high-quality Stitching Accessories, Mechanical & Electrical Fittings.</p>

                  <div class="d-md-flex align-items-center gap-4 mt-4">
                     <a href="products.php" class="btn btn-primary btn-lg">Explore Our Products <i class="fa fa-arrow-right ms-2"></i><span></span></a>
                     <a href="contact.php" class="btn btn-outline-light btn-lg">Contact Sales <i class="fa fa-phone ms-2"></i><span></span></a>
                  </div>

                  <div class="row mt-5 pt-3 border-top border-secondary border-opacity-50">
                     <div class="col-md-4 col-6 mb-3">
                        <div class="d-flex align-items-center gap-3 text-white">
                           <i class="fa fa-check-circle fa-2x text-primary"></i>
                           <div>
                              <strong class="d-block">Direct Imports</strong>
                              <small class="text-white-50">Raw Materials & Goods</small>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 col-6 mb-3">
                        <div class="d-flex align-items-center gap-3 text-white">
                           <i class="fa fa-tags fa-2x text-primary"></i>
                           <div>
                              <strong class="d-block">Competitive Rates</strong>
                              <small class="text-white-50">Market Best Pricing</small>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 col-12 mb-3">
                        <div class="d-flex align-items-center gap-3 text-white">
                           <i class="fa fa-truck fa-2x text-primary"></i>
                           <div>
                              <strong class="d-block">Timely Supply</strong>
                              <small class="text-white-50">Lahore & Faisalabad</small>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Hero Section End -->

   <!-- Catalog Products Showcase Section Start -->
   <section id="products" class="sec-padding bg-light border-top border-bottom position-relative">
      <div class="container">
         <div class="d-flex flex-wrap justify-content-between align-items-end mb-5">
            <div>
               <span class="sub-title wow fadeInUp">FEATURED PRODUCTS</span>
               <h2 class="sec-title mb-0">OUR PRODUCT RANGE</h2>
            </div>
            <a href="products.php" class="btn btn-primary rounded-pill px-4 mt-3 mt-md-0">
               View All Products <i class="fa fa-arrow-right ms-2"></i>
            </a>
         </div>

         <!-- CATEGORY 1: STITCHING ACCESSORIES -->
         <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-primary text-white p-4 p-md-5 d-flex flex-wrap justify-content-between align-items-center gap-3">
               <div>
                  <span class="badge bg-white text-primary text-uppercase fw-bold px-3 py-2 mb-2 rounded-pill">Product Category 1</span>
                  <h3 class="text-white fw-bold mb-0">1. Stitching Accessories</h3>
               </div>
               <div class="bg-white text-primary px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2 shadow-sm">
                  <i class="fa fa-tag"></i>
                  <span>PRICING: COMPETITIVE MARKET RATES</span>
               </div>
            </div>

            <div class="card-body p-4 p-md-5 bg-white">
               <div class="row gy-4">
                  <!-- Tapes -->
                  <div class="col-lg-4 col-md-6" id="stitching-tapes">
                     <div class="p-4 rounded-4 bg-light border h-100 hover-shadow transition">
                        <div class="mb-3 overflow-hidden rounded-3" style="height: 180px;">
                           <img src="uploads/products/tape-collection.jpg" alt="Tapes" class="w-100 h-100 object-fit-cover">
                        </div>
                        <h4 class="h5 fw-bold mb-2">Tapes</h4>
                        <ul class="list-group list-group-flush bg-transparent">
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>Masking Tape</li>
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>PVC Tape</li>
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>Dyed Tape</li>
                        </ul>
                     </div>
                  </div>

                  <!-- Elastic -->
                  <div class="col-lg-4 col-md-6" id="stitching-elastic">
                     <div class="p-4 rounded-4 bg-light border h-100 hover-shadow transition">
                        <div class="mb-3 overflow-hidden rounded-3" style="height: 180px;">
                           <img src="uploads/products/elastic-range.jpg" alt="Elastic" class="w-100 h-100 object-fit-cover">
                        </div>
                        <h4 class="h5 fw-bold mb-2">Elastic</h4>
                        <ul class="list-group list-group-flush bg-transparent">
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>Needleloom Elastic</li>
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>Plain Elastic</li>
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>All Kinds of Elastic</li>
                        </ul>
                     </div>
                  </div>

                  <!-- Buttons -->
                  <div class="col-lg-4 col-md-6" id="stitching-buttons">
                     <div class="p-4 rounded-4 bg-light border h-100 hover-shadow transition">
                        <div class="mb-3 overflow-hidden rounded-3" style="height: 180px;">
                           <img src="uploads/products/buttons-collection.jpg" alt="Buttons" class="w-100 h-100 object-fit-cover">
                        </div>
                        <h4 class="h5 fw-bold mb-2">Buttons</h4>
                        <ul class="list-group list-group-flush bg-transparent">
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>Sea Shell Buttons</li>
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>Shell Buttons</li>
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>Plastic & Tich Buttons</li>
                        </ul>
                     </div>
                  </div>

                  <!-- Threads & Cords -->
                  <div class="col-lg-6 col-md-6" id="stitching-threads">
                     <div class="p-4 rounded-4 bg-light border h-100 hover-shadow transition">
                        <div class="mb-3 overflow-hidden rounded-3" style="height: 180px;">
                           <img src="uploads/products/threads-cords.jpg" alt="Threads & Cords" class="w-100 h-100 object-fit-cover">
                        </div>
                        <h4 class="h5 fw-bold mb-2">Threads & Cords</h4>
                        <ul class="list-group list-group-flush bg-transparent">
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>Sateen Dori & Tag Dori</li>
                           <li class="list-group-item bg-transparent px-0"><i class="fa fa-caret-right text-primary me-2"></i>Local & Imported Varieties</li>
                        </ul>
                     </div>
                  </div>

                  <!-- Raw Materials -->
                  <div class="col-lg-6 col-md-12" id="stitching-raw">
                     <div class="p-4 rounded-4 bg-primary text-white h-100 hover-shadow transition d-flex flex-column justify-content-between">
                        <div class="mb-3 overflow-hidden rounded-3" style="height: 180px;">
                           <img src="uploads/products/raw-materials.jpg" alt="Raw Materials" class="w-100 h-100 object-fit-cover opacity-90">
                        </div>
                        <div>
                           <h4 class="h5 fw-bold text-white mb-2">Raw Materials</h4>
                           <p class="mb-0 text-white-50"><i class="fa fa-star text-warning me-2"></i>Own Import of High-Quality Raw Materials for industrial units.</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <!-- CATEGORY 2: MECHANICAL & ELECTRICAL FITTING -->
         <div class="card border-0 shadow-lg rounded-4 overflow-hidden" id="mechanical-electrical">
            <div class="card-header bg-dark text-white p-4 p-md-5 d-flex flex-wrap justify-content-between align-items-center gap-3">
               <div>
                  <span class="badge bg-primary text-white text-uppercase fw-bold px-3 py-2 mb-2 rounded-pill">Product Category 2</span>
                  <h3 class="text-white fw-bold mb-0">2. Mechanical & Electrical Fitting and Accessories</h3>
               </div>
               <div class="text-white-50 small text-uppercase tracking-wider fw-semibold">
                  PREMIUM QUALITY • RELIABLE PERFORMANCE • BUILT TO LAST
               </div>
            </div>

            <div class="card-body p-4 p-md-5 bg-white">
               <div class="row gy-4">
                  <!-- Pipes -->
                  <div class="col-lg-3 col-md-6" id="mech-pipes">
                     <div class="p-4 rounded-4 bg-light border h-100">
                        <div class="mb-3 overflow-hidden rounded-3" style="height: 140px;">
                           <img src="uploads/products/pipes-range.jpg" alt="Pipes" class="w-100 h-100 object-fit-cover">
                        </div>
                        <h4 class="h5 fw-bold text-dark mb-2">Pipes</h4>
                        <ul class="list-unstyled mb-0 small">
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>PVC Pipes</li>
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>GI Pipes</li>
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>Steel & HDPE Pipes</li>
                        </ul>
                     </div>
                  </div>

                  <!-- Sheets -->
                  <div class="col-lg-3 col-md-6" id="mech-sheets">
                     <div class="p-4 rounded-4 bg-light border h-100">
                        <div class="mb-3 overflow-hidden rounded-3" style="height: 140px;">
                           <img src="uploads/products/1c73fa383c976d5b0081c9cb4785a367.jpg" alt="Sheets" class="w-100 h-100 object-fit-cover">
                        </div>
                        <h4 class="h5 fw-bold text-dark mb-2">Sheets</h4>
                        <ul class="list-unstyled mb-0 small">
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>Steel Sheets</li>
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>Aluminum Sheets</li>
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>Galvanized & Fiber</li>
                        </ul>
                     </div>
                  </div>

                  <!-- Angles -->
                  <div class="col-lg-3 col-md-6" id="mech-angles">
                     <div class="p-4 rounded-4 bg-light border h-100">
                        <div class="mb-3 overflow-hidden rounded-3" style="height: 140px;">
                           <img src="uploads/products/a6bd0774af3480680d930ee534629e7a.jpg" alt="Angles" class="w-100 h-100 object-fit-cover">
                        </div>
                        <h4 class="h5 fw-bold text-dark mb-2">Angles</h4>
                        <ul class="list-unstyled mb-0 small">
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>Mild Steel Angles</li>
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>Stainless Steel</li>
                           <li class="py-1"><i class="fa fa-star text-warning me-2"></i>All Sizes Available</li>
                        </ul>
                     </div>
                  </div>

                  <!-- Channels -->
                  <div class="col-lg-3 col-md-6">
                     <div class="p-4 rounded-4 bg-light border h-100">
                        <div class="mb-3 overflow-hidden rounded-3" style="height: 140px;">
                           <img src="uploads/products/0bc2bde0a3ee76389ff715065bccf862.jpg" alt="Channels" class="w-100 h-100 object-fit-cover">
                        </div>
                        <h4 class="h5 fw-bold text-dark mb-2">Channels</h4>
                        <ul class="list-unstyled mb-0 small">
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>C-Channels</li>
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>U-Channels</li>
                           <li class="py-1"><i class="fa fa-check text-success me-2"></i>Steel Channels</li>
                        </ul>
                     </div>
                  </div>

                  <!-- Additional Items -->
                  <div class="col-12" id="mech-fittings">
                     <div class="p-4 p-md-5 rounded-4 bg-light border">
                        <h4 class="h5 fw-bold text-dark mb-4 text-uppercase border-bottom pb-3"><i class="fa fa-plus-circle text-primary me-2"></i>Additional Items & Accessories</h4>
                        <div class="row gy-3">
                           <div class="col-md-4 col-sm-6">
                              <div class="d-flex align-items-center gap-3"><i class="fa fa-bolt text-primary fs-5"></i><span>Electrical Wiring & Cables</span></div>
                           </div>
                           <div class="col-md-4 col-sm-6">
                              <div class="d-flex align-items-center gap-3"><i class="fa fa-toggle-on text-primary fs-5"></i><span>Switches & Sockets</span></div>
                           </div>
                           <div class="col-md-4 col-sm-6">
                              <div class="d-flex align-items-center gap-3"><i class="fa fa-shield-alt text-primary fs-5"></i><span>Circuit Breakers</span></div>
                           </div>
                           <div class="col-md-4 col-sm-6">
                              <div class="d-flex align-items-center gap-3"><i class="fa fa-tools text-primary fs-5"></i><span>Fasteners (Nuts, Bolts, Screws)</span></div>
                           </div>
                           <div class="col-md-4 col-sm-6">
                              <div class="d-flex align-items-center gap-3"><i class="fa fa-random text-primary fs-5"></i><span>Conduits & Trunking</span></div>
                           </div>
                           <div class="col-md-4 col-sm-6">
                              <div class="d-flex align-items-center gap-3"><i class="fa fa-link text-primary fs-5"></i><span>Pipe Fittings (Elbows, Tees, Reducers)</span></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Catalog Products Section End -->
   <!-- Vision & Mission Section Start -->
   <section class="sec-padding bg-white overflow-hidden">
      <div class="container">
         <div class="row align-items-stretch gy-4">
            <!-- Vision Card -->
            <div class="col-lg-5">
               <div class="p-4 p-md-5 rounded-4 bg-primary text-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative overflow-hidden">
                  <div class="position-relative z-1">
                     <span class="badge bg-white text-primary fw-bold text-uppercase px-3 py-2 mb-3 rounded-pill">Corporate Vision</span>
                     <h2 class="text-white fw-bold display-6 mb-4">OUR VISION</h2>
                     <p class="fs-5 text-white-50 lh-lg mb-0">
                        To become a leading global trading company recognized for excellence in quality, innovation, and customer satisfaction, while building long-term partnerships in local and international markets.
                     </p>
                  </div>
                  <div class="mt-4 pt-4 border-top border-white border-opacity-25 z-1">
                     <div class="d-flex align-items-center gap-3">
                        <i class="fa fa-globe fa-2x text-white-50"></i>
                        <span class="fw-semibold">Global Standards • Local Reliability</span>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Mission Card -->
            <div class="col-lg-7">
               <div class="p-4 p-md-5 rounded-4 bg-light text-dark h-100 shadow-sm border">
                  <span class="badge bg-secondary text-white fw-bold text-uppercase px-3 py-2 mb-3 rounded-pill">Our Core Purpose</span>
                  <h2 class="fw-bold display-6 mb-4">MISSION</h2>

                  <ul class="list-unstyled mb-0">
                     <li class="d-flex gap-3 mb-3">
                        <i class="fa fa-check-circle text-primary fs-4 mt-1"></i>
                        <span class="fs-6">To provide high-quality products that meet industry standards and customer expectations.</span>
                     </li>
                     <li class="d-flex gap-3 mb-3">
                        <i class="fa fa-check-circle text-primary fs-4 mt-1"></i>
                        <span class="fs-6">To ensure competitive pricing through efficient sourcing and direct import of raw materials.</span>
                     </li>
                     <li class="d-flex gap-3 mb-3">
                        <i class="fa fa-check-circle text-primary fs-4 mt-1"></i>
                        <span class="fs-6">To maintain strong relationships with clients through reliability, trust, and professional service.</span>
                     </li>
                     <li class="d-flex gap-3 mb-3">
                        <i class="fa fa-check-circle text-primary fs-4 mt-1"></i>
                        <span class="fs-6">To continuously expand our product range and market presence across industrial sectors.</span>
                     </li>
                     <li class="d-flex gap-3">
                        <i class="fa fa-check-circle text-primary fs-4 mt-1"></i>
                        <span class="fs-6">To contribute actively to the growth of the industrial and textile sectors.</span>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Vision & Mission Section End -->

   <!-- Executive Leadership Team Section Start -->
   <section class="sec-padding bg-white">
      <div class="container">
         <div class="sec-intro text-center mb-5">
            <span class="sub-title wow fadeInUp">Leadership & Management</span>
            <h2 class="sec-title">EXECUTIVE TEAM</h2>
            <p class="lead text-muted">Meet the visionary leaders behind Global Trading's success and operational excellence.</p>
         </div>

         <div class="row justify-content-center gy-4">
            <!-- CEO -->
            <div class="col-lg-4 col-md-6">
               <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4 hover-shadow transition">
                  <div class="mb-3 mx-auto overflow-hidden rounded-circle border border-3 border-primary shadow" style="width:130px; height:130px;">
                     <img src="images/team1.png" alt="Muhammad Asad Sheikh - CEO" class="img-fluid object-fit-cover w-100 h-100">
                  </div>
                  <span class="badge bg-primary text-white text-uppercase px-3 py-1 rounded-pill mx-auto mb-2" style="width: fit-content;">CEO</span>
                  <h3 class="h4 fw-bold text-dark mb-1">Muhammad Asad Sheikh</h3>
                  <p class="text-muted small">Chief Executive Officer</p>
               </div>
            </div>
            <!-- Marketing Director Lahore -->
            <div class="col-lg-4 col-md-6">
               <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4 hover-shadow transition">
                  <div class="mb-3 mx-auto overflow-hidden rounded-circle border border-3 border-primary shadow" style="width:130px; height:130px;">
                     <img src="images/team2.png" alt="Faiz Rasool - Marketing Director" class="img-fluid object-fit-cover w-100 h-85">
                  </div>
                  <span class="badge bg-primary text-white text-uppercase px-3 py-1 rounded-pill mx-auto mb-2" style="width: fit-content;">Marketing Director</span>
                  <h3 class="h4 fw-bold text-dark mb-1">Faiz Rasool</h3>
                  <p class="text-muted small">Lahore Division</p>
               </div>
            </div>
            <!-- Marketing Head Faisalabad -->
            <div class="col-lg-4 col-md-6">
               <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4 hover-shadow transition">
                  <div class="mb-3 mx-auto overflow-hidden rounded-circle border border-3 border-primary shadow" style="width:130px; height:130px;">
                     <img src="images/team3.png" alt="Rana Faizan - Marketing Head" class="img-fluid object-fit-cover w-100 h-85">
                  </div>
                  <span class="badge bg-primary text-white text-uppercase px-3 py-1 rounded-pill mx-auto mb-2" style="width: fit-content;">Marketing Head</span>
                  <h3 class="h4 fw-bold text-dark mb-1">Rana Faizan</h3>
                  <p class="text-muted small">Faisalabad Division</p>
               </div>
            </div>

            <!-- CFO -->
            <div class="col-lg-4 col-md-6">
               <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4 hover-shadow transition">
                  <div class="mb-3 mx-auto overflow-hidden rounded-circle border border-3 border-primary shadow" style="width:130px; height:130px;">
                     <img src="images/placeholder_img.png" alt="Irfan - CFO" class="img-fluid object-fit-cover w-100 h-85">
                  </div>
                  <span class="badge bg-primary text-white text-uppercase px-3 py-1 rounded-pill mx-auto mb-2" style="width: fit-content;">CFO</span>
                  <h3 class="h4 fw-bold text-dark mb-1">Irfan</h3>
                  <p class="text-muted small">Chief Financial Officer</p>
               </div>
            </div>
            <!-- Purchase -->
            <div class="col-lg-4 col-md-6">
               <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4 hover-shadow transition">
                  <div class="mb-3 mx-auto overflow-hidden rounded-circle border border-3 border-primary shadow" style="width:130px; height:130px;">
                     <img src="images/placeholder_img.png" alt="Irfan - CFO" class="img-fluid object-fit-cover w-100 h-85">
                  </div>
                  <span class="badge bg-primary text-white text-uppercase px-3 py-1 rounded-pill mx-auto mb-2" style="width: fit-content;">Purchase Manager </span>
                  <h3 class="h4 fw-bold text-dark mb-1">Rana Azam</h3>
                  <p class="text-muted small">Purchase Manager</p>
               </div>
            </div>
            <!-- Purchase -->
            <div class="col-lg-4 col-md-6">
               <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4 hover-shadow transition">
                  <div class="mb-3 mx-auto overflow-hidden rounded-circle border border-3 border-primary shadow" style="width:130px; height:130px;">
                     <img src="images/placeholder_img.png" alt="Irfan - CFO" class="img-fluid object-fit-cover w-100 h-85">
                  </div>
                  <span class="badge bg-primary text-white text-uppercase px-3 py-1 rounded-pill mx-auto mb-2" style="width: fit-content;">Purchase Officer </span>
                  <h3 class="h4 fw-bold text-dark mb-1">Muhammad Kashif</h3>
                  <p class="text-muted small">Purchase Officer</p>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Executive Leadership Team Section End -->

   <!-- DEDICATED OUR TRUSTED CUSTOMERS SECTION START -->
   <section id="trusted-clients" class="sec-padding bg-light border-top border-bottom">
      <div class="container">
         <div class="sec-intro text-center mb-5">
            <span class="sub-title wow fadeInUp">TRUSTED PARTNERS</span>
            <h2 class="sec-title">OUR TRUSTED CUSTOMERS</h2>
            <p class="lead text-muted">We take pride in building long-term partnerships with Pakistan's leading industrial and fashion brands.</p>
         </div>

         <div class="row g-4 justify-content-center">
            <?php if (!empty($trustedCustomers)): ?>
               <?php foreach ($trustedCustomers as $customer): ?>
                  <div class="col-lg-3 col-md-4 col-6">
                     <div class="p-4 rounded-4 bg-white border text-center shadow-sm h-100 d-flex flex-column align-items-center justify-content-center hover-shadow transition">
                        <?php
                        $logoFile = $customer['logo_image'] ?? '';
                        $logoPath = __DIR__ . '/uploads/customers/' . $logoFile;
                        if ($logoFile && file_exists($logoPath)):
                        ?>
                           <img src="uploads/customers/<?= htmlspecialchars($logoFile) ?>"
                              alt="<?= htmlspecialchars($customer['alt_text'] ?: $customer['name']) ?>"
                              style="max-height: 80px; max-width: 100%; object-fit: contain;">
                        <?php else: ?>
                           <div class="fw-semibold fs-5 text-dark text-uppercase" style="letter-spacing:1px;">
                              <?= htmlspecialchars($customer['name']) ?>
                           </div>
                        <?php endif; ?>
                     </div>
                  </div>
               <?php endforeach; ?>
            <?php else: ?>
               <div class="col-12 text-center text-muted py-4">
                  <i class="fa fa-handshake fa-2x mb-2 d-block"></i>
                  No trusted customers added yet.
               </div>
            <?php endif; ?>
         </div>
      </div>
   </section>
   <!-- DEDICATED OUR TRUSTED CUSTOMERS SECTION END -->

   <!-- Commitment & Office Locations Section Start -->
   <section class="sec-padding bg-dark text-white position-relative">
      <div class="container">
         <div class="row align-items-center gy-5">
            <div class="col-lg-6">
               <span class="badge bg-primary text-white text-uppercase px-3 py-2 mb-3 rounded-pill">Corporate Commitment</span>
               <h2 class="text-white display-5 fw-bold mb-4">Commitment to Quality & Service</h2>
               <p class="fs-5 text-white-50 lh-lg mb-4">
                  "We ensure quality products, reliable supply, and competitive pricing to meet the demands of our valued customers across industrial and textile sectors."
               </p>
               <div class="d-flex align-items-center gap-3">
                  <span class="icon-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width:50px; height:50px;"><i class="fa fa-handshake fa-lg"></i></span>
                  <div>
                     <h4 class="h6 fw-bold text-white mb-0">One Source. Complete Solution.</h4>
                     <small class="text-white-50">Direct raw material import & certified quality</small>
                  </div>
               </div>
            </div>

            <div class="col-lg-6">
               <div class="row gy-4">
                  <!-- Lahore Office -->
                  <div class="col-12">
                     <div class="p-4 rounded-4 bg-secondary bg-opacity-50 border border-secondary">
                        <div class="d-flex align-items-center gap-3 mb-2">
                           <i class="fa fa-map-marker-alt text-primary fs-4"></i>
                           <h3 class="h5 text-white fw-bold mb-0">Lahore Office</h3>
                        </div>
                        <p class="text-white-50 mb-2">Plot number 42, Street 1, Sector F, Phase 5, DHA, Lahore</p>
                        <p class="text-primary fw-semibold mb-0"><i class="fa fa-phone me-2"></i>0306-9249949 , 0300-4612749</p>
                     </div>
                  </div>

                  <!-- Faisalabad Office -->
                  <div class="col-12">
                     <div class="p-4 rounded-4 bg-secondary bg-opacity-50 border border-secondary">
                        <div class="d-flex align-items-center gap-3 mb-2">
                           <i class="fa fa-map-marker-alt text-primary fs-4"></i>
                           <h3 class="h5 text-white fw-bold mb-0">Faisalabad Office</h3>
                        </div>
                        <p class="text-white-50 mb-2">Main canal road near canal garden, Faisalabad</p>
                        <p class="text-primary fw-semibold mb-0"><i class="fa fa-phone me-2"></i>0309-2155551 , 0333-8396059</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Commitment & Office Locations Section End -->

   <!-- Footer Start -->
   <?php include 'include/footer.php'; ?>
   <!-- Footer End -->

   <!-- Scroll Top -->
   <div class="scroll-top">
      <svg class="progress-circle svg-content" height="100%" viewBox="-1 -1 102 102" width="100%">
         <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" fill="none" stroke="black" stroke-width="2">
         </path>
      </svg>
   </div>
   <!-- FooterLinks Start -->
   <?php include 'include/footerLinks.php'; ?>
   <!-- FooterLinks End -->
</body>

</html>