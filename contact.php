<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
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
               <li><a href="index.php#products">Product Catalog</a></li>
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
      <img src="images/promo-bg.jpg" alt="Contact Global Trading" class="jarallax-img">
      <div class="parallax-overly"></div>
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="promo-wrap position-relative text-center">
                  <h1 class="display-3 text-white fw-bold">Contact Global Trading</h1>
                  <p class="lead text-white-50">Reach out to our Lahore and Faisalabad sales offices.</p>
                  <nav aria-label="breadcrumb" class="d-inline-block bg-white breadcrumb-wrap rounded-pill px-4 py-2 mt-3 shadow-sm">
                     <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                     </ol>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Promo Section End -->

   <!-- Contact Info Section Start -->
   <section class="sec-padding bg-light">
      <div class="container">
         <div class="sec-intro text-center mb-5">
            <span class="sub-title wow fadeInUp">Our Office Locations</span>
            <h2 class="sec-title">Get In Touch With Us</h2>
            <p class="lead text-muted">For inquiries regarding Stitching Accessories, Mechanical & Electrical Fitting orders or bulk imports.</p>
         </div>

         <div class="row gy-4 justify-content-center">
            <!-- Lahore Office Card -->
            <div class="col-lg-6">
               <div class="card h-100 border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white">
                  <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
                     <span class="icon-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width:56px; height:56px;">
                        <i class="fa fa-building fa-lg"></i>
                     </span>
                     <div>
                        <span class="badge bg-primary text-white text-uppercase px-3 py-1 rounded-pill mb-1">Headquarters</span>
                        <h3 class="h4 fw-bold text-dark mb-0">Lahore Office</h3>
                     </div>
                  </div>

                  <ul class="list-unstyled fs-6 mb-4">
                     <li class="d-flex gap-3 mb-3">
                        <i class="fa fa-map-marker-alt text-primary fs-5 mt-1"></i>
                        <span><strong>Address:</strong> Plot number 42, Street 1, Sector F, Phase 5, DHA, Lahore</span>
                     </li>
                     <li class="d-flex gap-3 mb-3">
                        <i class="fa fa-phone text-primary fs-5 mt-1"></i>
                        <span><strong>Phone Numbers:</strong><br>
                           <a href="tel:03069249949" class="text-reset">0306-9249949</a> &bull; <a href="tel:03004612749" class="text-reset">0300-4612749</a>
                        </span>
                     </li>
                     <li class="d-flex gap-3 mb-3">
                        <i class="fa fa-user-tie text-primary fs-5 mt-1"></i>
                        <span><strong>Marketing Director:</strong> Faiz Rasool (Lahore Division)</span>
                     </li>
                     <li class="d-flex gap-3">
                        <i class="fa fa-envelope text-primary fs-5 mt-1"></i>
                        <span><strong>Email:</strong> <a href="mailto:globaltradin@gmail.com" class="text-reset">globaltradin@gmail.com</a></span>
                     </li>
                  </ul>
               </div>
            </div>

            <!-- Faisalabad Office Card -->
            <div class="col-lg-6">
               <div class="card h-100 border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white">
                  <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
                     <span class="icon-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width:56px; height:56px;">
                        <i class="fa fa-store fa-lg"></i>
                     </span>
                     <div>
                        <span class="badge bg-primary text-white text-uppercase px-3 py-1 rounded-pill mb-1">Regional Office</span>
                        <h3 class="h4 fw-bold text-dark mb-0">Faisalabad Office</h3>
                     </div>
                  </div>

                  <ul class="list-unstyled fs-6 mb-4">
                     <li class="d-flex gap-3 mb-3">
                        <i class="fa fa-map-marker-alt text-primary fs-5 mt-1"></i>
                        <span><strong>Address:</strong> Main canal road near canal garden, Faisalabad</span>
                     </li>
                     <li class="d-flex gap-3 mb-3">
                        <i class="fa fa-phone text-primary fs-5 mt-1"></i>
                        <span><strong>Phone Numbers:</strong><br>
                           <a href="tel:03092155551" class="text-reset">0309-2155551</a> &bull; <a href="tel:03338396059" class="text-reset">0333-8396059</a>
                        </span>
                     </li>
                     <li class="d-flex gap-3 mb-3">
                        <i class="fa fa-user-tie text-primary fs-5 mt-1"></i>
                        <span><strong>Marketing Head:</strong> Rana Faizan (Faisalabad Division)</span>
                     </li>
                     <li class="d-flex gap-3">
                        <i class="fa fa-envelope text-primary fs-5 mt-1"></i>
                        <span><strong>Email:</strong> <a href="mailto:globaltradin@gmail.com" class="text-reset">globaltradin@gmail.com</a></span>
                     </li>
                  </ul>
               </div>
            </div>
         </div>

         <!-- Contact Form -->
         <div class="row mt-5 pt-4">
            <div class="col-lg-8 mx-auto">
               <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white">
                  <h3 class="h4 fw-bold text-dark text-center mb-4">Send Us A Message</h3>
                  <form action="#" method="post">
                     <div class="row gy-3">
                        <div class="col-md-6">
                           <label class="form-label fw-semibold">Your Name</label>
                           <input type="text" class="form-control form-control-lg bg-light" placeholder="Full Name" required>
                        </div>
                        <div class="col-md-6">
                           <label class="form-label fw-semibold">Your Email</label>
                           <input type="email" class="form-control form-control-lg bg-light" placeholder="Email Address" required>
                        </div>
                        <div class="col-md-6">
                           <label class="form-label fw-semibold">Phone Number</label>
                           <input type="text" class="form-control form-control-lg bg-light" placeholder="Contact Number">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label fw-semibold">Product Category</label>
                           <select class="form-select form-select-lg bg-light">
                              <option selected>Select Product Category</option>
                              <option>Stitching Accessories (Tapes, Elastic, Buttons, Threads)</option>
                              <option>Mechanical & Electrical (Pipes, Sheets, Angles, Channels)</option>
                              <option>Direct Import Raw Materials</option>
                           </select>
                        </div>
                        <div class="col-12">
                           <label class="form-label fw-semibold">Message</label>
                           <textarea class="form-control bg-light" rows="4" placeholder="Write your inquiry details here..."></textarea>
                        </div>
                        <div class="col-12 text-center mt-4">
                           <button type="submit" class="btn btn-primary btn-lg px-5">Send Inquiry <i class="fa fa-paper-plane ms-2"></i></button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Contact Info Section End -->

   <!-- Footer Start -->
   <?php include 'include/footer.php'; ?>
   <!-- Footer End -->

   <!-- FooterLinks Start -->
   <?php include 'include/footerLinks.php'; ?>
   <!-- FooterLinks End -->
</body>
</html>