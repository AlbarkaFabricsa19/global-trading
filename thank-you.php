<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}

$inquiry = $_SESSION['inquiry_success'] ?? null;
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
         <div class="logo">
            <img src="images/logo.svg" alt="Global Trading Logo">
         </div>
         <button type="button" class="canvas-close" aria-label="Close">
            <svg width="33" height="34" viewBox="0 0 37 38" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path d="M9.19141 9.80762L27.5762 28.1924" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
               <path d="M9.19141 28.1924L27.5762 9.80761" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
         </button>
      </div>
      <p>Global Trading provides high-quality stitching accessories, mechanical and electrical fittings with reliable supply and direct imports.</p>
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
      <div class="social-share null mt-3">
         <a href="#"><i class="fab fa-facebook-f"></i></a>
         <a href="#"><i class="fab fa-x-twitter"></i></a>
         <a href="#"><i class="fab fa-instagram"></i></a>
         <a href="#"><i class="fab fa-linkedin-in"></i></a>
      </div>
      <a href="contact.php" class="btn btn-xs btn-primary mt-4"> Contact Us <i class="fa fa-arrow-right"></i><span></span></a>
   </div>
   <!-- Canvas Menu End -->

   <!-- Header Start (Visible with Dark Promo Hero) -->
   <?php include 'include/header.php'; ?>
   <!-- Header End -->

   <!-- Promo Section / Hero Banner Start -->
   <section class="promo-sec bg-cover jarallax" data-jarallax data-speed=".6">
      <img src="images/promo-bg.jpg" alt="Inquiry Received" class="jarallax-img">
      <div class="parallax-overly"></div>
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="promo-wrap position-relative text-center">
                  <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-pill px-4 py-2 mb-3 shadow-sm">
                     <i class="fa fa-check-circle text-success me-2 fs-5"></i>
                     <span class="text-success fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Submission Confirmed</span>
                  </div>
                  <h1 class="display-3 text-white fw-bold">Inquiry Received</h1>
                  <p class="lead text-white-50">Thank you for reaching out to Global Trading Industrial Solutions.</p>
                  <nav aria-label="breadcrumb" class="d-inline-block bg-white breadcrumb-wrap rounded-pill px-4 py-2 mt-3 shadow-sm">
                     <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="contact.php">Contact Us</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Confirmation</li>
                     </ol>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Promo Section End -->

   <!-- Confirmation Main Body Section -->
   <section class="sec-padding bg-light">
      <div class="container">
         
         <!-- Top Congratulatory Card -->
         <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
               <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 text-center bg-white position-relative overflow-hidden">
                  <div class="position-absolute top-0 start-0 w-100 bg-primary" style="height: 6px;"></div>

                  <!-- Success Check Icon -->
                  <div class="d-inline-flex align-items-center justify-content-center mx-auto mb-4 rounded-circle shadow-lg" 
                       style="width: 90px; height: 90px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff;">
                     <i class="fa fa-check" style="font-size: 2.8rem;"></i>
                  </div>

                  <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill text-uppercase fw-semibold mb-2 d-inline-block mx-auto">
                     <i class="fa fa-shield-alt me-1"></i> Registered In Global Trading Portal
                  </span>

                  <h2 class="display-6 fw-bold text-dark mb-2">
                     Thank You<?= !empty($inquiry['name']) ? ', <span class="text-primary">' . htmlspecialchars($inquiry['name']) . '</span>' : '' ?>!
                  </h2>
                  <p class="lead text-muted mb-0 mx-auto" style="max-width: 680px;">
                     Your inquiry details have been saved to our database. Our specialized product division will contact you with wholesale pricing, technical catalog, and delivery schedule.
                  </p>
               </div>
            </div>
         </div>

         <!-- Details & Quick Contact 2-Column Grid -->
         <div class="row g-4 justify-content-center">
            
            <!-- Left Column: Inquiry Summary -->
            <div class="col-lg-6">
               <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                  <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                     <div>
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem;">Inquiry Reference Number</small>
                        <h4 class="fw-bold text-primary mb-0">#INQ-<?= !empty($inquiry['id']) ? str_pad((string)$inquiry['id'], 5, '0', STR_PAD_LEFT) : '00001' ?></h4>
                     </div>
                     <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                        <i class="fa fa-clock text-primary me-1"></i> <?= htmlspecialchars($inquiry['created_at'] ?? date('d M Y, h:i A')) ?>
                     </span>
                  </div>

                  <div class="row g-3">
                     <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                           <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">Client / Company</small>
                           <strong class="text-dark fs-6"><?= htmlspecialchars($inquiry['name'] ?? 'Guest Customer') ?></strong>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                           <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">Email Address</small>
                           <strong class="text-dark fs-6" style="word-break: break-all;"><?= htmlspecialchars($inquiry['email'] ?? 'Not provided') ?></strong>
                        </div>
                     </div>
                     <?php if (!empty($inquiry['phone'])): ?>
                     <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                           <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">Phone Number</small>
                           <strong class="text-dark fs-6"><?= htmlspecialchars($inquiry['phone']) ?></strong>
                        </div>
                     </div>
                     <?php endif; ?>
                     <?php if (!empty($inquiry['category'])): ?>
                     <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                           <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">Category</small>
                           <span class="badge bg-secondary text-white rounded-pill px-2 py-1"><?= htmlspecialchars($inquiry['category']) ?></span>
                        </div>
                     </div>
                     <?php endif; ?>
                     <div class="col-12">
                        <div class="p-3 bg-light rounded-3">
                           <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 0.72rem;">Submitted Requirements</small>
                           <p class="text-secondary mb-0" style="white-space: pre-line; line-height: 1.5; font-size: 0.95rem;">
                              <?= htmlspecialchars($inquiry['message'] ?? 'Inquiry details recorded.') ?>
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Right Column: Direct Contact & Next Steps -->
            <div class="col-lg-5">
               <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white d-flex flex-column justify-content-between">
                  <div>
                     <h5 class="fw-bold text-dark mb-3"><i class="fa fa-phone-volume text-primary me-2"></i>Need Immediate Assistance?</h5>
                     <p class="text-muted small mb-4">
                        For urgent supply requirements, bulk orders, or custom quotes, call our sales directors directly:
                     </p>

                     <!-- Lahore Office Button -->
                     <div class="mb-3">
                        <a href="tel:03069249949" class="card border border-primary border-opacity-25 rounded-4 p-3 text-decoration-none transition-all hover-shadow bg-light d-flex flex-row align-items-center gap-3">
                           <div class="icon-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; min-width: 46px;">
                              <i class="fa fa-building"></i>
                           </div>
                           <div>
                              <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Lahore Division</small>
                              <strong class="text-dark fs-6">0306-9249949 / 0300-4612749</strong>
                           </div>
                        </a>
                     </div>

                     <!-- Faisalabad Office Button -->
                     <div class="mb-3">
                        <a href="tel:03092155551" class="card border border-primary border-opacity-25 rounded-4 p-3 text-decoration-none transition-all hover-shadow bg-light d-flex flex-row align-items-center gap-3">
                           <div class="icon-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; min-width: 46px;">
                              <i class="fa fa-store"></i>
                           </div>
                           <div>
                              <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Faisalabad Division</small>
                              <strong class="text-dark fs-6">0309-2155551 / 0333-8396059</strong>
                           </div>
                        </a>
                     </div>

                     <!-- WhatsApp Quick Button -->
                     <div>
                        <a href="https://wa.me/923004612749?text=<?= urlencode('Hello Global Trading, I submitted an inquiry with reference #INQ-' . (!empty($inquiry['id']) ? str_pad((string)$inquiry['id'], 5, '0', STR_PAD_LEFT) : '00001')) ?>" target="_blank" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                           <i class="fab fa-whatsapp fa-lg"></i> Chat With Sales On WhatsApp
                        </a>
                     </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="d-flex flex-wrap gap-2 pt-4 mt-3 border-top justify-content-between align-items-center">
                     <a href="products.php" class="btn btn-outline-primary rounded-pill px-3 py-2 btn-sm">
                        <i class="fa fa-boxes me-1"></i> Browse Products
                     </a>
                     <a href="index.php" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm">
                        <i class="fa fa-home me-1"></i> Home
                     </a>
                     <a href="contact.php" class="btn btn-link text-decoration-none text-muted btn-sm">
                        Submit Another
                     </a>
                  </div>
               </div>
            </div>

         </div>

      </div>
   </section>
   <!-- Confirmation Main Body Section End -->

   <!-- Footer Start -->
   <?php include 'include/footer.php'; ?>
   <!-- Footer End -->

   <!-- FooterLinks Start -->
   <?php include 'include/footerLinks.php'; ?>
   <!-- FooterLinks End -->
</body>
</html>
