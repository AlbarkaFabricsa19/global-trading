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
               <li><a href="products.php">Product Catalog</a></li>
               <li><a href="compliance.php">Compliance</a></li>
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
      <img src="images/promo-bg.jpg" alt="Compliance – Global Trading" class="jarallax-img">
      <div class="parallax-overly"></div>
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="promo-wrap position-relative text-center">
                  <h1 class="display-3 text-white fw-bold">Compliance</h1>
                  <p class="lead text-white-50">Our Certifications &amp; Standards</p>
                  <nav aria-label="breadcrumb" class="d-inline-block bg-white breadcrumb-wrap rounded-pill px-4 py-2 mt-3 shadow-sm">
                     <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Compliance</li>
                     </ol>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Promo Section End -->

   <!-- Certificate Section Start -->
   <section class="sec-padding bg-white">
      <div class="container">

         <!-- Section Heading -->
         <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
               <span class="badge bg-primary text-white fw-bold text-uppercase px-3 py-2 mb-3 rounded-pill">
                  <i class="fa fa-certificate me-2"></i>Official Certificate
               </span>
               <h2 class="fw-bold display-6 mb-3">Our Compliance Certificate</h2>
               <p class="text-muted fs-6">
                  Global Trading is committed to maintaining the highest standards of quality and regulatory compliance.
                  Below is our official certificate that validates our commitment to excellence.
               </p>
            </div>
         </div>

         <!-- Certificate Viewer Card -->
         <div class="row justify-content-center">
            <div class="col-lg-10">
               <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                  <!-- Card Header -->
                  <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between py-3 px-4">
                     <div class="d-flex align-items-center gap-3">
                        <i class="fa fa-file-pdf fa-lg"></i>
                        <span class="fw-semibold">certificate 11-81869.pdf</span>
                     </div>
                     <a href="uploads/certificates/certificate 11-81869.pdf"
                        download
                        class="btn btn-sm btn-light text-primary fw-semibold rounded-pill px-3">
                        <i class="fa fa-download me-1"></i> Download
                     </a>
                  </div>

                  <!-- PDF Embed -->
                  <div class="card-body p-0">
                     <iframe
                        src="uploads/certificates/certificate 11-81869.pdf"
                        style="width:100%; height:780px; border:none; display:block;"
                        title="Global Trading Compliance Certificate">
                        <p class="p-4 text-muted">
                           Your browser does not support embedded PDFs.
                           <a href="uploads/certificates/certificate 11-81869.pdf" class="text-primary fw-semibold">
                              Click here to download the certificate.
                           </a>
                        </p>
                     </iframe>
                  </div>

                  <!-- Card Footer -->
                  <div class="card-footer bg-light d-flex align-items-center justify-content-between px-4 py-3">
                     <small class="text-muted">
                        <i class="fa fa-shield-alt me-2 text-primary"></i>
                        This certificate is officially issued and verified.
                     </small>
                     <a href="uploads/certificates/certificate 11-81869.pdf"
                        target="_blank"
                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-external-link-alt me-1"></i> Open in New Tab
                     </a>
                  </div>

               </div>
            </div>
         </div>

      </div>
   </section>
   <!-- Certificate Section End -->

   <!-- Footer Start -->
   <?php include 'include/footer.php'; ?>
   <!-- Footer End -->

   <!-- Footer Links Start -->
   <?php include 'include/footerLinks.php'; ?>
   <!-- Footer Links End -->

</body>
</html>
