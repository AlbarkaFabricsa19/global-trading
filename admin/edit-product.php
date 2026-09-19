<?php
// admin/edit-product.php - Edit Product Form
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
requireAdmin();

$pdo = getDBConnection();
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: products.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: products.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $pricing_tag = sanitizeInput($_POST['pricing_tag'] ?? 'COMPETITIVE MARKET RATES');
    $short_desc = sanitizeInput($_POST['short_desc'] ?? '');
    $full_desc = sanitizeInput($_POST['full_desc'] ?? '');
    $items_raw = trim($_POST['items_list'] ?? '');
    $status = isset($_POST['status']) ? 1 : 0;

    $itemsArray = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $items_raw))));
    $items_list = json_encode($itemsArray);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    if (empty($name) || $category_id <= 0) {
        $error = "Product Name and Category are required.";
    } else {
        $imageName = $product['image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                if ($fileSize <= 5 * 1024 * 1024) {
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../uploads/products/';

                    if (!file_exists($uploadFileDir)) {
                        mkdir($uploadFileDir, 0777, true);
                    }

                    $dest_path = $uploadFileDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $imageName = $newFileName;
                    } else {
                        $error = "Failed to upload new image file.";
                    }
                } else {
                    $error = "Uploaded image exceeds 5MB size limit.";
                }
            } else {
                $error = "Upload failed. Allowed image formats: " . implode(',', $allowedExtensions);
            }
        }

        if (empty($error)) {
            $stmt = $pdo->prepare("
                UPDATE products 
                SET category_id = ?, name = ?, slug = ?, pricing_tag = ?, short_desc = ?, full_desc = ?, items_list = ?, image = ?, status = ?
                WHERE id = ?
            ");
            if ($stmt->execute([$category_id, $name, $slug, $pricing_tag, $short_desc, $full_desc, $items_list, $imageName, $status, $id])) {
                $success = "Product updated successfully!";
                // Refresh product data
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
            } else {
                $error = "Database error while updating product.";
            }
        }
    }
}

$itemsText = '';
$itemsDecoded = json_decode($product['items_list'] ?? '[]', true);
if (is_array($itemsDecoded)) {
    $itemsText = implode(", ", $itemsDecoded);
}

$pageTitle = 'Edit Product #' . $product['id'] . ' - Global Trading Admin';
require_once __DIR__ . '/header.php';
?>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="container-fluid px-lg-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa fa-edit me-2 text-primary"></i>Edit Product #<?= $product['id'] ?></h2>
            <p class="text-muted mb-0">Update product specifications, category, pricing, or images.</p>
        </div>
        <a href="products.php" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fa fa-arrow-left me-1"></i> Back to Products
        </a>
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

    <div class="card border-0 bg-white p-4 p-md-5">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Name *</label>
                        <input type="text" name="name" class="form-control form-control-lg" required value="<?= htmlspecialchars($product['name']) ?>">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category *</label>
                            <select name="category_id" class="form-select form-select-lg" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?> (<?= ucfirst($cat['section_type']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pricing / Badge Tag</label>
                            <input type="text" name="pricing_tag" class="form-control form-control-lg" value="<?= htmlspecialchars($product['pricing_tag']) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Short Description</label>
                        <textarea name="short_desc" class="form-control" rows="2"><?= htmlspecialchars($product['short_desc']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Description & Specifications</label>
                        <textarea name="full_desc" class="form-control" rows="5"><?= htmlspecialchars($product['full_desc']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Items / Sub-varieties Included</label>
                        <textarea name="items_list" class="form-control" rows="3"><?= htmlspecialchars($itemsText) ?></textarea>
                        <small class="text-muted">Comma or line separated item names.</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light border p-4 text-center mb-4">
                        <label class="form-label fw-bold text-dark mb-3"><i class="fa fa-image me-1"></i> Product Image</label>
                        <div class="mb-3">
                            <?php 
                                $imgPath = '../uploads/products/' . $product['image'];
                                $displayImg = (file_exists(__DIR__ . '/../uploads/products/' . $product['image']) && !empty($product['image'])) 
                                    ? $imgPath 
                                    : '../images/logo.svg';
                            ?>
                            <img id="imgPreview" src="<?= htmlspecialchars($displayImg) ?>" alt="Preview" class="img-fluid rounded border shadow-sm" style="max-height: 200px; object-fit: contain;">
                        </div>
                        <input type="file" name="image" id="imageInput" class="form-control" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted d-block mt-2">Leave blank to keep current image.</small>
                    </div>

                    <div class="card bg-light border p-4">
                        <label class="form-label fw-bold text-dark mb-2"><i class="fa fa-toggle-on me-1"></i> Publication Status</label>
                        <div class="form-check form-switch fs-5">
                            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" <?= $product['status'] == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label fs-6" for="statusSwitch">Published (Active)</label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3">
                            <i class="fa fa-save me-2"></i> Update Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imgPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php require_once __DIR__ . '/footer.php'; ?>
