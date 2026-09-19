<?php
// admin/products.php - Manage All Catalog Products
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
requireAdmin();

$pdo = getDBConnection();

// Fetch all categories for filter dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// Handle search and category filtering
$search = sanitizeInput($_GET['search'] ?? '');
$catFilter = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          WHERE 1=1";

$params = [];

if (!empty($search)) {
    $query .= " AND (p.name LIKE ? OR p.short_desc LIKE ? OR p.full_desc LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($catFilter > 0) {
    $query .= " AND p.category_id = ?";
    $params[] = $catFilter;
}

$query .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

$pageTitle = 'Manage Products - Global Trading Admin';
require_once __DIR__ . '/header.php';
?>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="container-fluid px-lg-4 py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa fa-boxes me-2 text-primary"></i>Manage Catalog Products</h2>
            <p class="text-muted mb-0">Add, update, or remove products from the Global Trading catalog.</p>
        </div>
        <a href="add-product.php" class="btn btn-primary rounded-pill px-4">
            <i class="fa fa-plus me-2"></i>Add New Product
        </a>
    </div>

    <!-- Filters & Search Form -->
    <div class="card p-3 p-md-4 mb-4 bg-white border-0">
        <form method="GET" action="" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search by product name or keyword..." value="<?= htmlspecialchars($search) ?>">
                </div>
            </div>
            
            <div class="col-md-4">
                <select name="category_id" class="form-select bg-light">
                    <option value="0">All Product Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $catFilter == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?> (<?= ucfirst($cat['section_type']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fa fa-filter me-1"></i> Filter</button>
                <a href="products.php" class="btn btn-outline-secondary"><i class="fa fa-undo"></i></a>
            </div>
        </form>
    </div>

    <!-- Products List Table -->
    <div class="card border-0 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 70px;">ID</th>
                            <th style="width: 80px;">Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Pricing Tag</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th class="text-end pe-4" style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fa fa-box-open fa-3x text-muted opacity-50 mb-3 d-block"></i>
                                    No products matching your search criteria were found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?= $p['id'] ?></td>
                                    <td>
                                        <?php 
                                            $imgPath = '../uploads/products/' . $p['image'];
                                            $displayImg = (file_exists(__DIR__ . '/../uploads/products/' . $p['image']) && !empty($p['image'])) 
                                                ? $imgPath 
                                                : '../images/logo.svg';
                                        ?>
                                        <img src="<?= htmlspecialchars($displayImg) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="rounded border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <a href="../product-detail.php?id=<?= $p['id'] ?>" target="_blank" class="fw-bold text-dark text-decoration-none">
                                            <?= htmlspecialchars($p['name']) ?>
                                        </a>
                                        <small class="d-block text-muted"><?= htmlspecialchars($p['slug']) ?></small>
                                    </td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($p['category_name']) ?></span></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['pricing_tag']) ?></span></td>
                                    <td>
                                        <?php 
                                            $items = json_decode($p['items_list'] ?? '[]', true);
                                            if (is_array($items) && !empty($items)) {
                                                echo '<span class="badge bg-info text-white">' . count($items) . ' items</span>';
                                            } else {
                                                echo '<span class="text-muted small">None</span>';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($p['status'] == 1): ?>
                                            <span class="badge bg-success"><i class="fa fa-check me-1"></i>Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Draft</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="../product-detail.php?id=<?= $p['id'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill me-1" title="Preview on website"><i class="fa fa-eye"></i></a>
                                        <a href="edit-product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill me-1" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a href="delete-product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Are you sure you want to delete this product?');" title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 text-muted small px-4 py-3">
            Showing <strong><?= count($products) ?></strong> product(s) in catalog
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
