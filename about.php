<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
// Load DB & fetch trusted customers
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
               <li><a href="about.php">About Us</a></li>
               <li><a href="products.php">Product Catalog</a></li>
               <li><a href="index.php#trusted-clients">Our Trusted Clients</a></li>
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

   <!-- Promo Section Start -->
   <section class="promo-sec bg-cover jarallax" data-jarallax data-speed=".6">
      <img src="images/promo-bg.jpg" alt="About Global Trading" class="jarallax-img">
      <div class="parallax-overly"></div>
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="promo-wrap position-relative text-center">
                  <h1 class="display-3 text-white fw-bold">About Global Trading</h1>
                  <p class="lead text-white-50">Industrial & Trading Solutions Importer & Supplier</p>
                  <nav aria-label="breadcrumb" class="d-inline-block bg-white breadcrumb-wrap rounded-pill px-4 py-2 mt-3 shadow-sm">
                     <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">About Us</li>
                     </ol>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Promo Section End -->

   <!-- Vision & Mission Section Start -->
   <section class="sec-padding bg-white">
      <div class="container">
         <div class="row align-items-stretch gy-4">
            <!-- Vision Card -->
            <div class="col-lg-5">
               <div class="p-4 p-md-5 rounded-4 bg-primary text-white h-100 shadow-sm d-flex flex-column justify-content-between">
                  <div>
                     <span class="badge bg-white text-primary fw-bold text-uppercase px-3 py-2 mb-3 rounded-pill">Company Vision</span>
                     <h2 class="text-white fw-bold display-6 mb-4">OUR VISION</h2>
                     <p class="fs-5 text-white-50 lh-lg mb-0">
                        To become a leading global trading company recognized for excellence in quality, innovation, and customer satisfaction, while building long-term partnerships in local and international markets.
                     </p>
                  </div>
                  <div class="mt-4 pt-4 border-top border-white border-opacity-25">
                     <div class="d-flex align-items-center gap-3">
                        <i class="fa fa-award fa-2x text-warning"></i>
                        <span class="fw-semibold">Recognized for Excellence & Innovation</span>
                     </div>
                  </div>
               </div>
            </div>
            
            <!-- Mission Card -->
            <div class="col-lg-7">
               <div class="p-4 p-md-5 rounded-4 bg-light text-dark h-100 shadow-sm border">
                  <span class="badge bg-secondary text-white fw-bold text-uppercase px-3 py-2 mb-3 rounded-pill">Company Mission</span>
                  <h2 class="fw-bold display-6 mb-4">OUR MISSION</h2>
                  
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
                        <span class="fs-6">To continuously expand our product range and market presence.</span>
                     </li>
                     <li class="d-flex gap-3">
                        <i class="fa fa-check-circle text-primary fs-4 mt-1"></i>
                        <span class="fs-6">To contribute to the growth of the industrial and textile sectors.</span>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Vision & Mission Section End -->

   <!-- Executive Leadership Section -->
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
   </section>F

   <!-- DEDICATED OUR TRUSTED CUSTOMERS SECTION -->
   <section class="sec-padding bg-white">
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
                     <div class="p-4 rounded-4 bg-light border text-center shadow-sm h-100 d-flex flex-column align-items-center justify-content-center hover-shadow transition">
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
                        <small class="text-muted mt-2"><?= htmlspecialchars($customer['alt_text'] ?: '') ?></small>
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

   <!-- Footer Start -->
   <?php include 'include/footer.php'; ?>
   <!-- Footer End -->

   <!-- FooterLinks Start -->
   <?php include 'include/footerLinks.php'; ?>
   <!-- FooterLinks End -->
</body>
</html>
