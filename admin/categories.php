<?php
// admin/categories.php - Manage Product Categories
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
requireAdmin();

$pdo = getDBConnection();

$error = '';
$success = '';

// Handle Category Addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = sanitizeInput($_POST['name'] ?? '');
    $section_type = sanitizeInput($_POST['section_type'] ?? 'stitching');
    $description = sanitizeInput($_POST['description'] ?? '');
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    if (empty($name)) {
        $error = "Category Name cannot be empty.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, section_type, description) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$name, $slug, $section_type, $description]);
            $success = "Category created successfully!";
        } catch (PDOException $e) {
            $error = "Category with this name or slug already exists.";
        }
    }
}

// Handle Category Deletion
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    if ($delId > 0) {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$delId]);
        $success = "Category deleted!";
        header("Refresh:1; url=categories.php");
    }
}

$categories = $pdo->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.id ASC")->fetchAll();

$pageTitle = 'Product Categories - Global Trading Admin';
require_once __DIR__ . '/header.php';
?>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="container-fluid px-lg-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa fa-tags me-2 text-primary"></i>Manage Product Categories</h2>
            <p class="text-muted mb-0">Organize catalog items under Stitching Accessories or Mechanical & Electrical sections.</p>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3" role="alert">
            <i class="fa fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Add Category Form -->
        <div class="col-lg-4">
            <div class="card border-0 bg-white p-4">
                <h5 class="fw-bold mb-3"><i class="fa fa-plus-circle text-primary me-2"></i>Add New Category</h5>
                <form method="POST" action="">
                    <input type="hidden" name="add_category" value="1">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Zippers & Fasteners" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Section Type *</label>
                        <select name="section_type" class="form-select" required>
                            <option value="stitching">1. Stitching Accessories</option>
                            <option value="mechanical_electrical">2. Mechanical & Electrical Fittings</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Category summary..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3">
                        <i class="fa fa-save me-1"></i> Save Category
                    </button>
                </form>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="col-lg-8">
            <div class="card border-0 bg-white">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="fa fa-list me-2 text-primary"></i>Existing Categories (<?= count($categories) ?>)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 70px;">ID</th>
                                <th>Name</th>
                                <th>Section</th>
                                <th>Products</th>
                                <th class="text-end pe-4" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?= $cat['id'] ?></td>
                                    <td>
                                        <strong class="text-dark d-block"><?= htmlspecialchars($cat['name']) ?></strong>
                                        <small class="text-muted"><?= htmlspecialchars($cat['slug']) ?></small>
                                    </td>
                                    <td>
                                        <?php if ($cat['section_type'] === 'stitching'): ?>
                                            <span class="badge bg-primary">Stitching Accessories</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Mechanical & Electrical</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= $cat['product_count'] ?> Products</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="categories.php?delete_id=<?= $cat['id'] ?>" class="btn btn-sm text-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this category?');"><i class="fa fa-trash me-1"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
