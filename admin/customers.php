<?php
// admin/customers.php - Manage Trusted Customers
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
requireAdmin();

$pdo = getDBConnection();
$success = '';
$error = '';

// --- Handle POST Actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ADD NEW CUSTOMER
    if ($action === 'add') {
        $name       = trim($_POST['name'] ?? '');
        $alt_text   = trim($_POST['alt_text'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $status     = isset($_POST['status']) ? 1 : 0;

        if (empty($name)) {
            $error = 'Customer name is required.';
        } else {
            $logo_image = null;
            if (!empty($_FILES['logo_image']['name'])) {
                $uploadDir = __DIR__ . '/../uploads/customers/';
                $ext       = strtolower(pathinfo($_FILES['logo_image']['name'], PATHINFO_EXTENSION));
                $allowed   = ['jpg','jpeg','png','gif','webp','svg'];
                if (!in_array($ext, $allowed)) {
                    $error = 'Invalid file type. Allowed: jpg, jpeg, png, gif, webp, svg';
                } elseif ($_FILES['logo_image']['size'] > 2 * 1024 * 1024) {
                    $error = 'File too large. Max size: 2MB';
                } else {
                    $filename   = 'customer_' . time() . '_' . uniqid() . '.' . $ext;
                    $targetPath = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $targetPath)) {
                        $logo_image = $filename;
                    } else {
                        $error = 'Failed to upload logo. Check folder permissions.';
                    }
                }
            }
            if (empty($error)) {
                $stmt = $pdo->prepare("INSERT INTO trusted_customers (name, logo_image, alt_text, sort_order, status) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $logo_image, $alt_text, $sort_order, $status]);
                $success = "Customer '<strong>" . htmlspecialchars($name) . "</strong>' added successfully!";
            }
        }
    }

    // UPDATE CUSTOMER
    elseif ($action === 'edit') {
        $id         = (int)($_POST['id'] ?? 0);
        $name       = trim($_POST['name'] ?? '');
        $alt_text   = trim($_POST['alt_text'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $status     = isset($_POST['status']) ? 1 : 0;

        if (empty($name) || $id <= 0) {
            $error = 'Invalid data.';
        } else {
            $existing = $pdo->prepare("SELECT logo_image FROM trusted_customers WHERE id = ?");
            $existing->execute([$id]);
            $current    = $existing->fetch();
            $logo_image = $current['logo_image'] ?? null;

            if (!empty($_FILES['logo_image']['name'])) {
                $uploadDir = __DIR__ . '/../uploads/customers/';
                $ext       = strtolower(pathinfo($_FILES['logo_image']['name'], PATHINFO_EXTENSION));
                $allowed   = ['jpg','jpeg','png','gif','webp','svg'];
                if (!in_array($ext, $allowed)) {
                    $error = 'Invalid file type. Allowed: jpg, jpeg, png, gif, webp, svg';
                } elseif ($_FILES['logo_image']['size'] > 2 * 1024 * 1024) {
                    $error = 'File too large. Max size: 2MB';
                } else {
                    $filename   = 'customer_' . time() . '_' . uniqid() . '.' . $ext;
                    $targetPath = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $targetPath)) {
                        if ($logo_image && file_exists($uploadDir . $logo_image)) { unlink($uploadDir . $logo_image); }
                        $logo_image = $filename;
                    } else {
                        $error = 'Failed to upload logo. Check folder permissions.';
                    }
                }
            }
            if (isset($_POST['remove_logo']) && !empty($logo_image)) {
                $uploadDir = __DIR__ . '/../uploads/customers/';
                if (file_exists($uploadDir . $logo_image)) { unlink($uploadDir . $logo_image); }
                $logo_image = null;
            }
            if (empty($error)) {
                $stmt = $pdo->prepare("UPDATE trusted_customers SET name=?, logo_image=?, alt_text=?, sort_order=?, status=? WHERE id=?");
                $stmt->execute([$name, $logo_image, $alt_text, $sort_order, $status, $id]);
                $success = "Customer updated successfully!";
            }
        }
    }

    // TOGGLE STATUS
    elseif ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $pdo->prepare("UPDATE trusted_customers SET status = 1 - status WHERE id = ?")->execute([$id]);
            $success = "Status updated.";
        }
    }
}

// Handle DELETE via GET
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    if ($delId > 0) {
        $existing = $pdo->prepare("SELECT logo_image FROM trusted_customers WHERE id = ?");
        $existing->execute([$delId]);
        $toDelete = $existing->fetch();
        if ($toDelete && $toDelete['logo_image']) {
            $logoPath = __DIR__ . '/../uploads/customers/' . $toDelete['logo_image'];
            if (file_exists($logoPath)) unlink($logoPath);
        }
        $pdo->prepare("DELETE FROM trusted_customers WHERE id = ?")->execute([$delId]);
        $success = "Customer deleted successfully.";
    }
}

// Fetch all customers
$customers = $pdo->query("SELECT * FROM trusted_customers ORDER BY sort_order ASC, id ASC")->fetchAll();

$pageTitle = 'Trusted Customers - Global Trading Admin';
require_once __DIR__ . '/header.php';
?>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="container-fluid px-lg-4 py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa fa-handshake text-primary me-2"></i>Trusted Customers</h2>
            <p class="text-muted mb-0">Manage logos &amp; info for the "Our Trusted Customers" catalog portfolio.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-1"></i> Add Customer
        </button>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fa fa-check-circle me-2"></i><?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width:60px;">#</th>
                            <th style="width:90px;">Logo</th>
                            <th>Customer Name</th>
                            <th>Alt Text</th>
                            <th style="width:80px;">Order</th>
                            <th style="width:110px;">Status</th>
                            <th class="text-end pe-4" style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa fa-inbox fa-2x mb-2 d-block"></i>No customers yet.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customers as $c): ?>
                                <tr>
                                    <td class="ps-4 text-muted fw-semibold"><?= $c['id'] ?></td>
                                    <td>
                                        <?php if ($c['logo_image'] && file_exists(__DIR__ . '/../uploads/customers/' . $c['logo_image'])): ?>
                                            <img src="../uploads/customers/<?= htmlspecialchars($c['logo_image']) ?>"
                                                 alt="<?= htmlspecialchars($c['alt_text'] ?: $c['name']) ?>"
                                                 class="logo-thumb">
                                        <?php else: ?>
                                            <div class="no-logo-badge">No Logo</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-semibold text-dark"><?= htmlspecialchars($c['name']) ?></td>
                                    <td class="text-muted small"><?= htmlspecialchars($c['alt_text'] ?: '—') ?></td>
                                    <td><span class="badge bg-secondary"><?= $c['sort_order'] ?></span></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="toggle">
                                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-link p-0 text-decoration-none">
                                                <?php if ($c['status']): ?>
                                                    <span class="badge bg-success"><i class="fa fa-toggle-on me-1"></i>Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><i class="fa fa-toggle-off me-1"></i>Inactive</span>
                                                <?php endif; ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary rounded-pill me-1"
                                                onclick="openEditModal(<?= $c['id'] ?>, <?= htmlspecialchars(json_encode($c['name'])) ?>, <?= htmlspecialchars(json_encode($c['alt_text'])) ?>, <?= $c['sort_order'] ?>, <?= $c['status'] ?>, <?= htmlspecialchars(json_encode($c['logo_image'] ?? '')) ?>)">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <a href="?delete=<?= $c['id'] ?>"
                                           class="btn btn-sm btn-outline-danger rounded-pill"
                                           onclick="return confirm('Delete this customer?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 text-muted small px-4 py-3">
            Total: <strong><?= count($customers) ?></strong> trusted customers
        </div>
    </div>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" enctype="multipart/form-data" class="modal-content rounded-4 border-0">
            <input type="hidden" name="action" value="add">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fa fa-plus-circle text-primary me-2"></i>Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Customer / Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. ETHNIC" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="status" id="addStatus" checked>
                            <label class="form-check-label fw-semibold" for="addStatus">Active</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alt Text <small class="text-muted fw-normal">(accessibility & SEO)</small></label>
                        <input type="text" name="alt_text" class="form-control" placeholder="e.g. Ethnic Fashion Brand Logo">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Logo Image <small class="text-muted fw-normal">(JPG, PNG, GIF, WebP, SVG - max 2MB)</small></label>
                        <div class="drop-zone" id="addDropZone" onclick="document.getElementById('addLogoInput').click()">
                            <i class="fa fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                            <p class="mb-0 text-muted small">Click to browse or drag &amp; drop logo here</p>
                            <img id="add-preview-img" src="" alt="Preview" style="max-height:90px; border-radius:8px; object-fit:contain; display:none; margin-top:10px;">
                        </div>
                        <input type="file" id="addLogoInput" name="logo_image" accept=".jpg,.jpeg,.png,.gif,.webp,.svg" class="d-none"
                               onchange="previewImage(this, 'add-preview-img')">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fa fa-save me-1"></i> Save Customer</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" enctype="multipart/form-data" class="modal-content rounded-4 border-0">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="editId">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fa fa-edit text-primary me-2"></i>Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Customer / Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" id="editSortOrder" class="form-control" min="0">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="status" id="editStatus">
                            <label class="form-check-label fw-semibold" for="editStatus">Active</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alt Text</label>
                        <input type="text" name="alt_text" id="editAltText" class="form-control">
                    </div>
                    <div class="col-12">
                        <div id="currentLogoWrap" class="mb-2 d-none">
                            <label class="form-label fw-semibold">Current Logo</label>
                            <div>
                                <img id="editCurrentLogo" src="" alt="Current Logo"
                                     style="max-height:80px; border-radius:8px; border:1px solid #dee2e6; padding:4px; background:#fff;">
                            </div>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="remove_logo" id="removeLogoCheck">
                                <label class="form-check-label text-danger small" for="removeLogoCheck">Remove current logo</label>
                            </div>
                        </div>
                        <label class="form-label fw-semibold">Upload New Logo <small class="text-muted fw-normal">(optional)</small></label>
                        <div class="drop-zone" id="editDropZone" onclick="document.getElementById('editLogoInput').click()">
                            <i class="fa fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                            <p class="mb-0 text-muted small">Click to browse or drag &amp; drop new logo</p>
                            <img id="edit-preview-img" src="" alt="Preview" style="max-height:90px; border-radius:8px; object-fit:contain; display:none; margin-top:10px;">
                        </div>
                        <input type="file" id="editLogoInput" name="logo_image" accept=".jpg,.jpeg,.png,.gif,.webp,.svg" class="d-none"
                               onchange="previewImage(this, 'edit-preview-img')">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fa fa-save me-1"></i> Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    }
}

function openEditModal(id, name, altText, sortOrder, status, logoFile) {
    document.getElementById('editId').value        = id;
    document.getElementById('editName').value      = name;
    document.getElementById('editAltText').value   = altText;
    document.getElementById('editSortOrder').value = sortOrder;
    document.getElementById('editStatus').checked  = status == 1;
    const logoWrap = document.getElementById('currentLogoWrap');
    const logoImg  = document.getElementById('editCurrentLogo');
    if (logoFile) {
        logoImg.src = '../uploads/customers/' + logoFile;
        logoWrap.classList.remove('d-none');
    } else {
        logoWrap.classList.add('d-none');
    }
    document.getElementById('edit-preview-img').style.display = 'none';
    document.getElementById('editLogoInput').value = '';
    document.getElementById('removeLogoCheck').checked = false;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

// Drag & drop
['addDropZone', 'editDropZone'].forEach(zoneId => {
    const zone = document.getElementById(zoneId);
    if (!zone) return;
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.background = '#dce8ff'; });
    zone.addEventListener('dragleave', () => { zone.style.background = ''; });
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.style.background = '';
        const inputId = zoneId === 'addDropZone' ? 'addLogoInput' : 'editLogoInput';
        const prevId  = zoneId === 'addDropZone' ? 'add-preview-img' : 'edit-preview-img';
        const inp = document.getElementById(inputId);
        inp.files = e.dataTransfer.files;
        previewImage(inp, prevId);
    });
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
