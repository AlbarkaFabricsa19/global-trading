<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
// product-detail.php - Single Product View & Specs
require_once __DIR__ . '/config/db.php';

$pdo = getDBConnection();
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: products.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name, c.slug as category_slug, c.section_type, c.description as category_desc
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ? AND p.status = 1
");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: products.php");
    exit;
}

// Fetch related products from same category
$relStmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 1 LIMIT 3");
$relStmt->execute([$product['category_id'], $id]);
$relatedProducts = $relStmt->fetchAll();

$items = json_decode($product['items_list'] ?? '[]', true);
if (!is_array($items)) $items = [];
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

   <!-- Header Start -->
   <?php include 'include/header.php'; ?>
   <!-- Header End -->

   <!-- Promo Banner Start -->
   <section class="promo-sec bg-cover jarallax" data-jarallax data-speed=".6">
      <img src="images/promo-bg.jpg" alt="<?= htmlspecialchars($product['name']) ?>" class="jarallax-img">
      <div class="parallax-overly"></div>
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="promo-wrap position-relative text-center">
                  <span class="badge bg-primary text-white text-uppercase px-3 py-2 rounded-pill mb-3">
                     <?= htmlspecialchars($product['category_name']) ?>
                  </span>
                  <h1 class="display-4 text-white fw-bold"><?= htmlspecialchars($product['name']) ?></h1>
                  <p class="lead text-white-50"><?= htmlspecialchars($product['pricing_tag']) ?></p>
                  <nav aria-label="breadcrumb" class="d-inline-block bg-white breadcrumb-wrap rounded-pill px-4 py-2 mt-3 shadow-sm">
                     <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="products.php">Products Catalog</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
                     </ol>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Promo Banner End -->

   <!-- Single Product Details Start -->
   <section class="sec-padding bg-white">
      <div class="container">
         <div class="row g-5 align-items-start">
            <!-- Product Photo -->
            <div class="col-lg-6">
               <div class="card border-0 shadow-lg rounded-4 overflow-hidden p-3 bg-light text-center">
                  <?php 
                     $imgPath = 'uploads/products/' . $product['image'];
                     $displayImg = (file_exists(__DIR__ . '/uploads/products/' . $product['image']) && !empty($product['image'])) 
                         ? $imgPath 
                         : 'images/logo.svg';
                  ?>
                  <img src="<?= htmlspecialchars($displayImg) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded-4 w-100 object-fit-cover shadow-sm" style="max-height: 450px;">
               </div>
            </div>

            <!-- Product Specs Info -->
            <div class="col-lg-6">
               <div class="ps-lg-3">
                  <span class="badge bg-secondary text-white text-uppercase px-3 py-2 rounded-pill mb-2">
                     Category: <?= htmlspecialchars($product['category_name']) ?>
                  </span>
                  <h2 class="display-6 fw-bold text-dark mb-3"><?= htmlspecialchars($product['name']) ?></h2>
                  
                  <div class="p-3 bg-light rounded-3 border mb-4 d-inline-block">
                     <span class="text-primary fw-bold fs-5"><i class="fa fa-tags me-2"></i></span>
                     <span class="fw-bold text-dark fs-5"><?= htmlspecialchars($product['pricing_tag']) ?></span>
                  </div>

                  <div class="mb-4">
                     <h4 class="h5 fw-bold text-dark border-bottom pb-2">Product Summary</h4>
                     <p class="fs-6 text-muted lh-lg"><?= nl2br(htmlspecialchars($product['short_desc'])) ?></p>
                  </div>

                  <?php if (!empty($items)): ?>
                     <div class="mb-4">
                        <h4 class="h5 fw-bold text-dark border-bottom pb-2"><i class="fa fa-list-check text-primary me-2"></i>Varieties & Items Included</h4>
                        <div class="row gy-2 mt-1">
                           <?php foreach ($items as $item): ?>
                              <div class="col-sm-6">
                                 <div class="d-flex align-items-center gap-2 p-2 rounded bg-light border">
                                    <i class="fa fa-check-circle text-success fs-5"></i>
                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($item) ?></span>
                                 </div>
                              </div>
                           <?php endforeach; ?>
                        </div>
                     </div>
                  <?php endif; ?>

                  <div class="mb-4">
                     <h4 class="h5 fw-bold text-dark border-bottom pb-2">Full Specifications & Quality Standards</h4>
                     <p class="fs-6 text-muted lh-lg"><?= nl2br(htmlspecialchars($product['full_desc'])) ?></p>
                  </div>

                  <!-- Sales Inquiry Box -->
                  <div class="card border-primary border-2 bg-light p-4 rounded-4 shadow-sm mt-4">
                     <h4 class="h5 fw-bold text-dark mb-2"><i class="fa fa-headset text-primary me-2"></i>Inquire For Bulk Orders</h4>
                     <p class="text-muted small mb-3">Contact Global Trading sales offices in Lahore or Faisalabad for instant price quotes and bulk availability.</p>
                     
                     <div class="d-flex flex-wrap gap-3">
                        <a href="tel:03069249949" class="btn btn-primary rounded-pill px-4"><i class="fa fa-phone me-2"></i>Call Lahore Sales (0306-9249949)</a>
                        <a href="tel:03092155551" class="btn btn-dark rounded-pill px-4"><i class="fa fa-phone me-2"></i>Call Faisalabad Sales (0309-2155551)</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <!-- Related Products Grid -->
         <?php if (!empty($relatedProducts)): ?>
            <div class="mt-5 pt-5 border-top">
               <h3 class="fw-bold mb-4">Related Products in <?= htmlspecialchars($product['category_name']) ?></h3>
               <div class="row g-4">
                  <?php foreach ($relatedProducts as $rel): ?>
                     <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-light">
                           <div style="height: 180px;" class="overflow-hidden">
                              <?php 
                                 $relImg = 'uploads/products/' . $rel['image'];
                                 $displayRel = (file_exists(__DIR__ . '/uploads/products/' . $rel['image']) && !empty($rel['image'])) 
                                     ? $relImg 
                                     : 'images/logo.svg';
                              ?>
                              <img src="<?= htmlspecialchars($displayRel) ?>" alt="<?= htmlspecialchars($rel['name']) ?>" class="w-100 h-100 object-fit-cover">
                           </div>
                           <div class="card-body p-4">
                              <h4 class="h6 fw-bold text-dark mb-2"><?= htmlspecialchars($rel['name']) ?></h4>
                              <p class="text-muted small mb-3"><?= htmlspecialchars(substr($rel['short_desc'], 0, 80)) ?>...</p>
                              <a href="product-detail.php?id=<?= $rel['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill w-100">View Product</a>
                           </div>
                        </div>
                     </div>
                  <?php endforeach; ?>
               </div>
            </div>
         <?php endif; ?>
      </div>
   </section>
   <!-- Single Product Details End -->

   <!-- Footer Start -->
   <?php include 'include/footer.php'; ?>
   <!-- Footer End -->

   <!-- FooterLinks Start -->
   <?php include 'include/footerLinks.php'; ?>
   <!-- FooterLinks End -->
</body>
</html>
