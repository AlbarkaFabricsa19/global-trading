<?php
// admin/add-product.php - Add New Product Form
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
requireAdmin();

$pdo = getDBConnection();
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

    // Convert comma/newline separated items into JSON array
    $itemsArray = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $items_raw))));
    $items_list = json_encode($itemsArray);

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    if (empty($name) || $category_id <= 0) {
        $error = "Product Name and Category are required.";
    } else {
        // Image Upload Handling
        $imageName = 'default-product.jpg';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                if ($fileSize <= 5 * 1024 * 1024) { // Max 5MB
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../uploads/products/';

                    if (!file_exists($uploadFileDir)) {
                        mkdir($uploadFileDir, 0777, true);
                    }

                    $dest_path = $uploadFileDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $imageName = $newFileName;
                    } else {
                        $error = "Failed to upload image file to server.";
                    }
                } else {
                    $error = "Uploaded image exceeds 5MB size limit.";
                }
            } else {
                $error = "Upload failed. Allowed image types: " . implode(',', $allowedExtensions);
            }
        }

        if (empty($error)) {
            $stmt = $pdo->prepare("
                INSERT INTO products (category_id, name, slug, pricing_tag, short_desc, full_desc, items_list, image, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            if ($stmt->execute([$category_id, $name, $slug, $pricing_tag, $short_desc, $full_desc, $items_list, $imageName, $status])) {
                $success = "Product added successfully!";
                header("Refresh:1; url=products.php");
            } else {
                $error = "Database error while adding product.";
            }
        }
    }
}

$pageTitle = 'Add Product - Global Trading Admin';
require_once __DIR__ . '/header.php';
?>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="container-fluid px-lg-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa fa-plus-circle me-2 text-primary"></i>Add New Catalog Product</h2>
            <p class="text-muted mb-0">Fill in the product details and upload product photos.</p>
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
            <i class="fa fa-check-circle me-2"></i><?= htmlspecialchars($success) ?> Redirecting...
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 bg-white p-4 p-md-5">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Name *</label>
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="e.g. Masking & PVC Tapes Variety" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category *</label>
                            <select name="category_id" class="form-select form-select-lg" required>
                                <option value="">-- Select Product Category --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (($_POST['category_id'] ?? 0) == $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?> (<?= ucfirst($cat['section_type']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pricing / Badge Tag</label>
                            <input type="text" name="pricing_tag" class="form-control form-control-lg" placeholder="COMPETITIVE MARKET RATES" value="<?= htmlspecialchars($_POST['pricing_tag'] ?? 'COMPETITIVE MARKET RATES') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Short Description</label>
                        <textarea name="short_desc" class="form-control" rows="2" placeholder="Brief summary of the product..."><?= htmlspecialchars($_POST['short_desc'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Description & Specifications</label>
                        <textarea name="full_desc" class="form-control" rows="5" placeholder="Detailed product features, quality standards, and industrial usages..."><?= htmlspecialchars($_POST['full_desc'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Items / Sub-varieties Included</label>
                        <textarea name="items_list" class="form-control" rows="3" placeholder="Enter items separated by comma or new lines (e.g. Masking Tape, PVC Tape, Dyed Tape)"><?= htmlspecialchars($_POST['items_list'] ?? '') ?></textarea>
                        <small class="text-muted">Enter items separated by commas or line breaks.</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light border p-4 text-center mb-4">
                        <label class="form-label fw-bold text-dark mb-3"><i class="fa fa-image me-1"></i> Product Image Upload</label>
                        <div class="mb-3">
                            <img id="imgPreview" src="../images/logo.svg" alt="Preview" class="img-fluid rounded border shadow-sm" style="max-height: 200px; object-fit: contain;">
                        </div>
                        <input type="file" name="image" id="imageInput" class="form-control" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted d-block mt-2">Allowed formats: JPG, PNG, WEBP (Max 5MB)</small>
                    </div>

                    <div class="card bg-light border p-4">
                        <label class="form-label fw-bold text-dark mb-2"><i class="fa fa-toggle-on me-1"></i> Publication Status</label>
                        <div class="form-check form-switch fs-5">
                            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" checked>
                            <label class="form-check-label fs-6" for="statusSwitch">Publish immediately (Active)</label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3">
                            <i class="fa fa-save me-2"></i> Save Product
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
