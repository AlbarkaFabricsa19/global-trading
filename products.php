<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
// products.php - Public Interactive Product Catalog
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/db_setup.php'; // Ensures DB & data seeded

$pdo = getDBConnection();

// Fetch all categories for filter tabs
$categories = $pdo->query("SELECT * FROM categories ORDER BY section_type ASC, name ASC")->fetchAll();

$search = sanitizeInput($_GET['search'] ?? '');
$catSlug = sanitizeInput($_GET['category'] ?? '');
$sectionFilter = sanitizeInput($_GET['section'] ?? '');

$query = "SELECT p.*, c.name as category_name, c.slug as category_slug, c.section_type 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          WHERE p.status = 1";

$params = [];

if (!empty($search)) {
    $query .= " AND (p.name LIKE ? OR p.short_desc LIKE ? OR p.full_desc LIKE ? OR p.items_list LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($catSlug)) {
    $query .= " AND c.slug = ?";
    $params[] = $catSlug;
}

if (!empty($sectionFilter)) {
    $query .= " AND c.section_type = ?";
    $params[] = $sectionFilter;
}

$query .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
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
      <img src="images/promo-bg.jpg" alt="Products Catalog" class="jarallax-img">
      <div class="parallax-overly"></div>
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="promo-wrap position-relative text-center">
                  <span class="badge bg-primary text-white text-uppercase px-3 py-2 rounded-pill mb-3">Company Catalog</span>
                  <h1 class="display-3 text-white fw-bold">OUR PRODUCTS CATALOG</h1>
                  <p class="lead text-white-50">Stitching Accessories & Mechanical/Electrical Fitting Solutions</p>
                  <nav aria-label="breadcrumb" class="d-inline-block bg-white breadcrumb-wrap rounded-pill px-4 py-2 mt-3 shadow-sm">
                     <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Products</li>
                     </ol>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Promo Banner End -->

<style>
/* Products Page Filter & Tab Styling */
.filter-search-box {
    border: 1.5px solid #e2e8f0;
    border-radius: 50px;
    background-color: #f8fafc;
    transition: all 0.25s ease;
    height: 50px;
}
.filter-search-box:focus-within {
    border-color: #00607a;
    background-color: #ffffff;
    box-shadow: 0 0 0 0.25rem rgba(0, 96, 122, 0.15);
}
.filter-search-input {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    font-size: 0.95rem;
    height: 100%;
}
.filter-category-select {
    height: 50px;
    border-radius: 50px !important;
    border: 1.5px solid #e2e8f0;
    background-color: #f8fafc;
    font-size: 0.95rem;
    padding-left: 20px;
    padding-right: 40px;
    transition: all 0.25s ease;
}
.filter-category-select:focus {
    border-color: #00607a;
    background-color: #ffffff;
    box-shadow: 0 0 0 0.25rem rgba(0, 96, 122, 0.15);
}
.btn-filter-submit {
    height: 50px;
    border-radius: 50px !important;
    background-color: #00607a !important;
    border: 1.5px solid #00607a !important;
    color: #ffffff !important;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 24px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 96, 122, 0.18);
}
.btn-filter-submit:hover {
    background-color: #047c9d !important;
    border-color: #047c9d !important;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(0, 96, 122, 0.28);
}
.btn-filter-reset {
    height: 50px;
    border-radius: 50px !important;
    border: 1.5px solid #cbd5e1;
    background-color: #ffffff;
    color: #64748b;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 16px;
    transition: all 0.25s ease;
    text-decoration: none;
}
.btn-filter-reset:hover {
    border-color: #e84c4e;
    color: #e84c4e;
    background-color: #fff1f2;
}

/* Category Filter Tabs */
.category-filter-tab {
    background-color: transparent !important;
    color: #00607a !important;
    border: 1.5px solid #00607a !important;
    border-radius: 50px !important;
    padding: 10px 24px !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px;
    transition: all 0.25s ease !important;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(0, 96, 122, 0.08);
}
.category-filter-tab:hover {
    background-color: #00607a !important;
    color: #ffffff !important;
    border-color: #00607a !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 96, 122, 0.25);
}
.category-filter-tab.active {
    background-color: #00607a !important;
    color: #ffffff !important;
    border-color: #00607a !important;
    box-shadow: 0 6px 18px rgba(0, 96, 122, 0.3);
}

/* =============================================
   Product Card Image Hover Animation
   (same slide+blur style as .hover-img)
   ============================================= */
.product-img-wrap {
    overflow: hidden;
}
.product-thumb-img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform 0.55s ease, filter 0.55s ease;
    transform: translateX(0) scaleX(1);
    filter: blur(0);
    z-index: 1;
    position: relative;
}
.product-img-wrap:hover .product-thumb-img {
    transform: translateX(-6%) scaleX(1.08);
    filter: blur(1.5px);
}

/* Overlay */
.product-img-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        160deg,
        rgba(0, 96, 122, 0.75) 0%,
        rgba(1, 25, 31, 0.85) 100%
    );
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: translateX(50%) scaleX(2);
    filter: blur(10px);
    transition: opacity 0.5s ease, transform 0.5s ease, filter 0.5s ease;
    z-index: 2;
}
.product-img-wrap:hover .product-img-overlay {
    opacity: 1;
    transform: translateX(0) scaleX(1);
    filter: blur(0);
}

/* Overlay button */
.product-overlay-btn {
    color: #ffffff;
    border: 2px solid rgba(255,255,255,0.8);
    border-radius: 50px;
    padding: 10px 22px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    background-color: rgba(255,255,255,0.12);
    backdrop-filter: blur(4px);
}
.product-overlay-btn:hover {
    background-color: #ffffff;
    color: #00607a;
    border-color: #ffffff;
    transform: scale(1.05);
}
</style>

   <!-- Products Section Start -->
   <section class="sec-padding bg-light">
      <div class="container">
         <!-- Search & Filter Bar -->
         <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-5 bg-white">
            <form method="GET" action="" class="row g-3 align-items-center">
               <div class="col-lg-5 col-md-6">
                  <div class="filter-search-box d-flex align-items-center px-3">
                     <i class="fa fa-search text-primary me-2"></i>
                     <input type="text" name="search" class="form-control filter-search-input" placeholder="Search products (e.g. Masking Tape, Elastic, Pipes, Sheets)..." value="<?= htmlspecialchars($search) ?>">
                  </div>
               </div>

               <div class="col-lg-4 col-md-6">
                  <select name="category" class="form-select filter-category-select" onchange="this.form.submit()">
                     <option value="">All Categories & Products</option>
                     <optgroup label="1. Stitching Accessories">
                        <?php foreach ($categories as $c): if ($c['section_type'] === 'stitching'): ?>
                           <option value="<?= $c['slug'] ?>" <?= $catSlug === $c['slug'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                        <?php endif; endforeach; ?>
                     </optgroup>
                     <optgroup label="2. Mechanical & Electrical">
                        <?php foreach ($categories as $c): if ($c['section_type'] === 'mechanical_electrical'): ?>
                           <option value="<?= $c['slug'] ?>" <?= $catSlug === $c['slug'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                        <?php endif; endforeach; ?>
                     </optgroup>
                  </select>
               </div>

               <div class="col-lg-3 col-md-12 d-flex gap-2">
                  <button type="submit" class="btn-filter-submit flex-grow-1">
                     <i class="fa fa-filter me-2"></i>Filter
                  </button>
                  <?php if (!empty($search) || !empty($catSlug) || !empty($sectionFilter)): ?>
                     <a href="products.php" class="btn-filter-reset" title="Reset all filters">
                        <i class="fa fa-times me-1"></i> Reset
                      </a>
                  <?php endif; ?>
               </div>
            </form>
         </div>

         <!-- Section Quick Tabs -->
         <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
            <a href="products.php" class="category-filter-tab <?= (empty($sectionFilter) && empty($catSlug)) ? 'active' : '' ?>">
               <i class="fa fa-th-large me-2"></i>All Products
            </a>
            <a href="products.php?section=stitching" class="category-filter-tab <?= $sectionFilter === 'stitching' ? 'active' : '' ?>">
               <i class="fa fa-cut me-2"></i>1. Stitching Accessories
            </a>
            <a href="products.php?section=mechanical_electrical" class="category-filter-tab <?= $sectionFilter === 'mechanical_electrical' ? 'active' : '' ?>">
               <i class="fa fa-cogs me-2"></i>2. Mechanical & Electrical
            </a>
         </div>

         <!-- Products Grid -->
         <div class="row g-4">
            <?php if (empty($products)): ?>
               <div class="col-12 text-center py-5">
                  <div class="p-5 rounded-4 bg-white border shadow-sm">
                     <i class="fa fa-search fa-4x text-muted mb-3"></i>
                     <h3 class="h4 fw-bold text-dark">No Products Found</h3>
                     <p class="text-muted">We couldn't find any products matching your filter criteria. Try resetting search filters.</p>
                     <a href="products.php" class="btn btn-primary rounded-pill px-4"><i class="fa fa-sync me-2"></i>View All Products</a>
                  </div>
               </div>
            <?php else: ?>
               <?php foreach ($products as $p): ?>
                  <div class="col-lg-4 col-md-6">
                     <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition">
               <!-- Product Image Thumbnail -->
                        <div class="product-img-wrap position-relative overflow-hidden bg-light text-center" style="height: 240px;">
                           <?php 
                              $imgPath = 'uploads/products/' . $p['image'];
                              $displayImg = (file_exists(__DIR__ . '/uploads/products/' . $p['image']) && !empty($p['image'])) 
                                  ? $imgPath 
                                  : 'images/logo.svg';
                           ?>
                           <img src="<?= htmlspecialchars($displayImg) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-thumb-img w-100 h-100 object-fit-cover">
                           <!-- Hover Overlay -->
                           <div class="product-img-overlay">
                              <a href="product-detail.php?id=<?= $p['id'] ?>" class="product-overlay-btn">
                                 <i class="fa fa-eye me-2"></i>View Details
                              </a>
                           </div>
                           <span class="position-absolute top-0 start-0 m-3 badge bg-primary text-white text-uppercase px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem; z-index: 3;">
                              <?= htmlspecialchars($p['category_name']) ?>
                           </span>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                           <div>
                              <div class="d-flex justify-content-between align-items-center mb-2">
                                 <small class="text-primary fw-bold text-uppercase"><i class="fa fa-tag me-1"></i><?= htmlspecialchars($p['pricing_tag']) ?></small>
                              </div>
                              <h3 class="h5 fw-bold text-dark mb-2">
                                 <a href="product-detail.php?id=<?= $p['id'] ?>" class="text-reset text-decoration-none hover-primary">
                                    <?= htmlspecialchars($p['name']) ?>
                                 </a>
                              </h3>
                              <p class="text-muted small mb-3 lh-sm">
                                 <?= htmlspecialchars(substr($p['short_desc'] ?? '', 0, 110)) ?>...
                              </p>

                              <!-- Sub-items list tags -->
                              <?php 
                                 $items = json_decode($p['items_list'] ?? '[]', true);
                                 if (is_array($items) && !empty($items)):
                              ?>
                                 <div class="d-flex flex-wrap gap-1 mb-3">
                                    <?php foreach (array_slice($items, 0, 4) as $item): ?>
                                       <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.75rem;"><i class="fa fa-check text-success me-1"></i><?= htmlspecialchars($item) ?></span>
                                    <?php endforeach; ?>
                                    <?php if (count($items) > 4): ?>
                                       <span class="badge bg-light text-muted border fw-normal" style="font-size: 0.75rem;">+<?= count($items) - 4 ?> more</span>
                                    <?php endif; ?>
                                 </div>
                              <?php endif; ?>
                           </div>

                           <div class="pt-3 border-top mt-2 d-flex justify-content-between align-items-center">
                              <a href="product-detail.php?id=<?= $p['id'] ?>" class="btn btn-primary rounded-pill px-4 btn-sm w-100">
                                 View Details & Specifications <i class="fa fa-arrow-right ms-2"></i>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            <?php endif; ?>
         </div>
      </div>
   </section>
   <!-- Products Section End -->

   <!-- Footer Start -->
   <?php include 'include/footer.php'; ?>
   <!-- Footer End -->

   <!-- FooterLinks Start -->
   <?php include 'include/footerLinks.php'; ?>
   <!-- FooterLinks End -->
</body>
</html>
