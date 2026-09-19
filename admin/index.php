<?php
// admin/index.php - Admin Dashboard
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
requireAdmin();

$pdo = getDBConnection();

// Fetch summary metrics
$totalProducts   = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$activeProducts  = $pdo->query("SELECT COUNT(*) FROM products WHERE status = 1")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalCustomers  = $pdo->query("SELECT COUNT(*) FROM trusted_customers")->fetchColumn();

// Fetch recent products
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    ORDER BY p.id DESC LIMIT 6
");
$stmt->execute();
$recentProducts = $stmt->fetchAll();

$pageTitle = 'Dashboard - Global Trading Admin';
require_once __DIR__ . '/header.php';
include __DIR__ . '/navbar.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fa fa-tachometer-alt me-2 text-primary"></i>Admin Overview</h2>
        <p class="text-muted mb-0">Overview of catalog products, categories, trusted clients, and quick actions.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="customers.php" class="btn btn-outline-primary rounded-pill px-3">
            <i class="fa fa-handshake me-1"></i> Manage Clients
        </a>
        <a href="add-product.php" class="btn btn-primary rounded-pill px-4">
            <i class="fa fa-plus me-1"></i> Add Product
        </a>
    </div>
</div>

<!-- Stat Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-primary text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 small" style="letter-spacing: 0.5px;">Total Products</h6>
                    <h2 class="display-6 fw-bold mb-0 text-white"><?= $totalProducts ?></h2>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="fa fa-boxes fa-lg text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-success text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 small" style="letter-spacing: 0.5px;">Active Items</h6>
                    <h2 class="display-6 fw-bold mb-0 text-white"><?= $activeProducts ?></h2>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="fa fa-check-circle fa-lg text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-info text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 small" style="letter-spacing: 0.5px;">Categories</h6>
                    <h2 class="display-6 fw-bold mb-0 text-white"><?= $totalCategories ?></h2>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="fa fa-tags fa-lg text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-secondary-theme text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 small" style="letter-spacing: 0.5px;">Trusted Clients</h6>
                    <h2 class="display-6 fw-bold mb-0 text-white"><?= $totalCustomers ?></h2>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="fa fa-handshake fa-lg text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Products Table -->
<div class="card border-0 bg-white">
    <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0"><i class="fa fa-clock text-primary me-2"></i>Recently Added Catalog Items</h5>
        <a href="products.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">View All Products</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 80px;">Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Pricing Tag</th>
                    <th>Status</th>
                    <th class="text-end pe-4" style="width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentProducts)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No products found. Click "Add New Product" to start.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentProducts as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <?php
                                $imgPath = '../uploads/products/' . $p['image'];
                                $displayImg = (file_exists(__DIR__ . '/../uploads/products/' . $p['image']) && !empty($p['image']))
                                    ? $imgPath
                                    : '../images/logo.svg';
                                ?>
                                <img src="<?= htmlspecialchars($displayImg) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="rounded border" style="width: 48px; height: 48px; object-fit: cover;">
                            </td>
                            <td>
                                <strong class="d-block text-dark"><?= htmlspecialchars($p['name']) ?></strong>
                                <small class="text-muted"><?= htmlspecialchars(substr($p['short_desc'] ?? '', 0, 55)) ?>...</small>
                            </td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($p['category_name']) ?></span></td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['pricing_tag']) ?></span></td>
                            <td>
                                <?php if ($p['status'] == 1): ?>
                                    <span class="badge bg-success"><i class="fa fa-check me-1"></i>Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="edit-product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary me-1 rounded-pill px-3" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="delete-product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this product?');" title="Delete"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
