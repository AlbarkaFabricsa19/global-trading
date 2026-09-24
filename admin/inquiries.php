<?php
// admin/inquiries.php - Manage Customer Contact Inquiries & Leads
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
requireAdmin();

$pdo = getDBConnection();
ensureInquiriesTable($pdo);

$success = '';
$error = '';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Quick Status Update
    if ($action === 'quick_status') {
        $id = (int)($_POST['id'] ?? 0);
        $newStatus = sanitizeInput($_POST['status'] ?? 'new');
        $allowedStatuses = ['new', 'contacted', 'in_progress', 'closed'];

        if ($id > 0 && in_array($newStatus, $allowedStatuses)) {
            $stmt = $pdo->prepare("UPDATE contact_inquiries SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$newStatus, $id]);
            $success = "Inquiry #INQ-" . str_pad((string)$id, 5, '0', STR_PAD_LEFT) . " status updated to '<strong>" . ucfirst(str_replace('_', ' ', $newStatus)) . "</strong>'!";
        } else {
            $error = "Invalid status update request.";
        }
    }

    // Full Details & Notes Update
    elseif ($action === 'update_details') {
        $id = (int)($_POST['id'] ?? 0);
        $status = sanitizeInput($_POST['status'] ?? 'new');
        $adminNotes = trim($_POST['admin_notes'] ?? '');
        $allowedStatuses = ['new', 'contacted', 'in_progress', 'closed'];

        if ($id > 0 && in_array($status, $allowedStatuses)) {
            $stmt = $pdo->prepare("UPDATE contact_inquiries SET status = ?, admin_notes = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$status, $adminNotes, $id]);
            $success = "Inquiry details and notes saved successfully!";
        } else {
            $error = "Failed to update inquiry details.";
        }
    }

    // Delete Inquiry
    elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM contact_inquiries WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Inquiry deleted successfully.";
        } else {
            $error = "Invalid inquiry ID.";
        }
    }

    // Bulk Status Update
    elseif ($action === 'bulk_action') {
        $selectedIds = $_POST['selected_ids'] ?? [];
        $bulkStatus = sanitizeInput($_POST['bulk_status'] ?? '');
        $allowedStatuses = ['new', 'contacted', 'in_progress', 'closed', 'delete'];

        if (!empty($selectedIds) && is_array($selectedIds) && in_array($bulkStatus, $allowedStatuses)) {
            $intIds = array_map('intval', $selectedIds);
            $placeholders = implode(',', array_fill(0, count($intIds), '?'));

            if ($bulkStatus === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM contact_inquiries WHERE id IN ($placeholders)");
                $stmt->execute($intIds);
                $success = count($intIds) . " inquiries deleted successfully.";
            } else {
                $stmt = $pdo->prepare("UPDATE contact_inquiries SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id IN ($placeholders)");
                $stmt->execute(array_merge([$bulkStatus], $intIds));
                $success = count($intIds) . " inquiries updated to '<strong>" . ucfirst(str_replace('_', ' ', $bulkStatus)) . "</strong>'!";
            }
        } else {
            $error = "Please select at least one inquiry and a valid action.";
        }
    }
}

// Counts for filter badges
$counts = [
    'all'         => (int)$pdo->query("SELECT COUNT(*) FROM contact_inquiries")->fetchColumn(),
    'new'         => (int)$pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE status = 'new'")->fetchColumn(),
    'contacted'   => (int)$pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE status = 'contacted'")->fetchColumn(),
    'in_progress' => (int)$pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE status = 'in_progress'")->fetchColumn(),
    'closed'      => (int)$pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE status = 'closed'")->fetchColumn(),
];

// Determine Active Filter (Default: 'new' as requested by user)
$statusFilter = isset($_GET['status']) ? sanitizeInput($_GET['status']) : 'new';
if (!in_array($statusFilter, ['new', 'contacted', 'in_progress', 'closed', 'all'])) {
    $statusFilter = 'new';
}

$search = sanitizeInput($_GET['search'] ?? '');

// Build Query
$query = "SELECT * FROM contact_inquiries WHERE 1=1";
$params = [];

if ($statusFilter !== 'all') {
    $query .= " AND status = ?";
    $params[] = $statusFilter;
}

if (!empty($search)) {
    $query .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR category LIKE ? OR message LIKE ? OR admin_notes LIKE ?)";
    $like = "%$search%";
    $params = array_merge($params, [$like, $like, $like, $like, $like, $like]);
}

$query .= " ORDER BY id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$inquiries = $stmt->fetchAll();

$pageTitle = 'Customer Inquiries & Leads - Global Trading Admin';
require_once __DIR__ . '/header.php';
include __DIR__ . '/navbar.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fa fa-envelope-open-text me-2 text-primary"></i>Customer Contact Inquiries</h2>
        <p class="text-muted mb-0">Review incoming queries, manage customer leads, update follow-up statuses, and track closed inquiries.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="inquiries.php?status=new" class="btn btn-outline-danger rounded-pill px-3 position-relative">
            <i class="fa fa-bell me-1"></i> New Queries Only
            <?php if ($counts['new'] > 0): ?>
                <span class="badge bg-danger rounded-pill ms-1"><?= $counts['new'] ?></span>
            <?php endif; ?>
        </a>
        <a href="../contact.php" target="_blank" class="btn btn-outline-primary rounded-pill px-3">
            <i class="fa fa-external-link-alt me-1"></i> View Contact Page
        </a>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="fa fa-check-circle me-2"></i><?= $success ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="fa fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Stat Summary / Filter Tabs -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl">
        <a href="inquiries.php?status=new" class="text-decoration-none">
            <div class="card border-0 p-3 h-100 rounded-4 transition-all shadow-sm <?= $statusFilter === 'new' ? 'bg-danger text-white ring-2 ring-danger' : 'bg-white' ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="<?= $statusFilter === 'new' ? 'text-white-50' : 'text-muted' ?> fw-bold text-uppercase" style="font-size:0.75rem;">New / Unread</small>
                        <h3 class="fw-bold mb-0 <?= $statusFilter === 'new' ? 'text-white' : 'text-danger' ?>"><?= $counts['new'] ?></h3>
                    </div>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center <?= $statusFilter === 'new' ? 'bg-white bg-opacity-20 text-white' : 'bg-danger bg-opacity-10 text-danger' ?>" style="width:40px; height:40px;">
                        <i class="fa fa-envelope fa-lg"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-4 col-xl">
        <a href="inquiries.php?status=contacted" class="text-decoration-none">
            <div class="card border-0 p-3 h-100 rounded-4 transition-all shadow-sm <?= $statusFilter === 'contacted' ? 'bg-info text-white' : 'bg-white' ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="<?= $statusFilter === 'contacted' ? 'text-white-50' : 'text-muted' ?> fw-bold text-uppercase" style="font-size:0.75rem;">Contacted</small>
                        <h3 class="fw-bold mb-0 <?= $statusFilter === 'contacted' ? 'text-white' : 'text-info' ?>"><?= $counts['contacted'] ?></h3>
                    </div>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center <?= $statusFilter === 'contacted' ? 'bg-white bg-opacity-20 text-white' : 'bg-info bg-opacity-10 text-info' ?>" style="width:40px; height:40px;">
                        <i class="fa fa-phone-alt fa-lg"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-4 col-xl">
        <a href="inquiries.php?status=in_progress" class="text-decoration-none">
            <div class="card border-0 p-3 h-100 rounded-4 transition-all shadow-sm <?= $statusFilter === 'in_progress' ? 'bg-warning text-dark' : 'bg-white' ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="<?= $statusFilter === 'in_progress' ? 'text-dark-50 text-secondary' : 'text-muted' ?> fw-bold text-uppercase" style="font-size:0.75rem;">In Progress</small>
                        <h3 class="fw-bold mb-0 text-warning"><?= $counts['in_progress'] ?></h3>
                    </div>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center <?= $statusFilter === 'in_progress' ? 'bg-dark bg-opacity-10 text-dark' : 'bg-warning bg-opacity-10 text-warning' ?>" style="width:40px; height:40px;">
                        <i class="fa fa-spinner fa-lg"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-4 col-xl">
        <a href="inquiries.php?status=closed" class="text-decoration-none">
            <div class="card border-0 p-3 h-100 rounded-4 transition-all shadow-sm <?= $statusFilter === 'closed' ? 'bg-success text-white' : 'bg-white' ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="<?= $statusFilter === 'closed' ? 'text-white-50' : 'text-muted' ?> fw-bold text-uppercase" style="font-size:0.75rem;">Closed</small>
                        <h3 class="fw-bold mb-0 <?= $statusFilter === 'closed' ? 'text-white' : 'text-success' ?>"><?= $counts['closed'] ?></h3>
                    </div>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center <?= $statusFilter === 'closed' ? 'bg-white bg-opacity-20 text-white' : 'bg-success bg-opacity-10 text-success' ?>" style="width:40px; height:40px;">
                        <i class="fa fa-check-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-md-4 col-xl">
        <a href="inquiries.php?status=all" class="text-decoration-none">
            <div class="card border-0 p-3 h-100 rounded-4 transition-all shadow-sm <?= $statusFilter === 'all' ? 'bg-secondary text-white' : 'bg-white' ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="<?= $statusFilter === 'all' ? 'text-white-50' : 'text-muted' ?> fw-bold text-uppercase" style="font-size:0.75rem;">All Queries</small>
                        <h3 class="fw-bold mb-0 <?= $statusFilter === 'all' ? 'text-white' : 'text-secondary' ?>"><?= $counts['all'] ?></h3>
                    </div>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center <?= $statusFilter === 'all' ? 'bg-white bg-opacity-20 text-white' : 'bg-secondary bg-opacity-10 text-secondary' ?>" style="width:40px; height:40px;">
                        <i class="fa fa-list-ul fa-lg"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="card p-3 p-md-4 mb-4 bg-white border-0 shadow-sm rounded-4">
    <form method="GET" action="inquiries.php" class="row g-3 align-items-center">
        <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter) ?>">

        <div class="col-md-7">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search by name, email, phone, category, or keyword..." value="<?= htmlspecialchars($search) ?>">
            </div>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select bg-light" onchange="this.form.submit()">
                <option value="new" <?= $statusFilter === 'new' ? 'selected' : '' ?>>🔴 New Queries (Default)</option>
                <option value="contacted" <?= $statusFilter === 'contacted' ? 'selected' : '' ?>>📞 Contacted Queries</option>
                <option value="in_progress" <?= $statusFilter === 'in_progress' ? 'selected' : '' ?>>⏳ In Progress Queries</option>
                <option value="closed" <?= $statusFilter === 'closed' ? 'selected' : '' ?>>✅ Closed Queries</option>
                <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>📋 All Queries</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill"><i class="fa fa-filter me-1"></i> Filter</button>
            <a href="inquiries.php?status=<?= htmlspecialchars($statusFilter) ?>" class="btn btn-outline-secondary rounded-circle px-3" title="Reset Search"><i class="fa fa-undo"></i></a>
        </div>
    </form>
</div>

<!-- Inquiries Table Card -->
<form method="POST" id="bulkForm">
    <input type="hidden" name="action" value="bulk_action">

    <div class="card border-0 bg-white shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white p-3 p-md-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-bold mb-0">
                    <?php if ($statusFilter === 'new'): ?>
                        <span class="text-danger"><i class="fa fa-bell me-2"></i>New Submissions</span>
                    <?php elseif ($statusFilter === 'contacted'): ?>
                        <span class="text-info"><i class="fa fa-phone-alt me-2"></i>Contacted Inquiries</span>
                    <?php elseif ($statusFilter === 'in_progress'): ?>
                        <span class="text-warning"><i class="fa fa-spinner me-2"></i>In Progress Inquiries</span>
                    <?php elseif ($statusFilter === 'closed'): ?>
                        <span class="text-success"><i class="fa fa-check-circle me-2"></i>Closed Inquiries</span>
                    <?php else: ?>
                        <span class="text-dark"><i class="fa fa-inbox me-2"></i>All Inquiries</span>
                    <?php endif; ?>
                </h5>
                <span class="badge bg-light text-dark border ms-2"><?= count($inquiries) ?> found</span>
            </div>

            <!-- Bulk Actions -->
            <div class="d-flex align-items-center gap-2">
                <select name="bulk_status" class="form-select form-select-sm bg-light" style="width: 170px;" required>
                    <option value="">Bulk Action...</option>
                    <option value="contacted">Mark Contacted</option>
                    <option value="in_progress">Mark In Progress</option>
                    <option value="closed">Mark Closed</option>
                    <option value="new">Mark as New</option>
                    <option value="delete">Delete Selected</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="return confirm('Apply bulk action to selected items?');">Apply</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 40px;">
                            <input type="checkbox" class="form-check-input" id="selectAll" onclick="toggleSelectAll(this)">
                        </th>
                        <th style="width: 80px;">Ref ID</th>
                        <th>Client Details</th>
                        <th>Category</th>
                        <th style="min-width: 220px;">Inquiry Message</th>
                        <th style="width: 140px;">Status</th>
                        <th style="width: 130px;">Date &amp; Time</th>
                        <th class="text-end pe-4" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inquiries)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="fa fa-inbox fa-3x mb-3 text-muted opacity-50"></i>
                                    <h5>No Inquiries Found</h5>
                                    <p class="small text-muted mb-3">
                                        <?php if ($statusFilter === 'new'): ?>
                                            There are no new unread inquiries right now.
                                        <?php else: ?>
                                            No inquiries matching your filter criteria.
                                        <?php endif; ?>
                                    </p>
                                    <a href="inquiries.php?status=all" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All Inquiries</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inquiries as $inq): ?>
                            <?php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $inq['phone'] ?? '');
                            if (strlen($cleanPhone) === 11 && substr($cleanPhone, 0, 1) === '0') {
                                $waPhone = '92' . substr($cleanPhone, 1);
                            } else {
                                $waPhone = $cleanPhone;
                            }
                            ?>
                            <tr class="<?= $inq['status'] === 'new' ? 'table-warning bg-opacity-25' : '' ?>">
                                <td class="ps-4">
                                    <input type="checkbox" name="selected_ids[]" value="<?= $inq['id'] ?>" class="form-check-input row-checkbox">
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-bold">#<?= str_pad((string)$inq['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                </td>
                                <td>
                                    <strong class="d-block text-dark"><?= htmlspecialchars($inq['name']) ?></strong>
                                    <div class="small text-muted d-flex align-items-center gap-2 mt-1">
                                        <a href="mailto:<?= htmlspecialchars($inq['email']) ?>" class="text-decoration-none text-muted" title="Send Email">
                                            <i class="fa fa-envelope text-primary me-1"></i><?= htmlspecialchars($inq['email']) ?>
                                        </a>
                                    </div>
                                    <?php if (!empty($inq['phone'])): ?>
                                        <div class="small mt-1 d-flex align-items-center gap-2">
                                            <a href="tel:<?= htmlspecialchars($inq['phone']) ?>" class="text-decoration-none text-dark fw-semibold" title="Call">
                                                <i class="fa fa-phone text-success me-1"></i><?= htmlspecialchars($inq['phone']) ?>
                                            </a>
                                            <?php if (!empty($waPhone)): ?>
                                                <a href="https://wa.me/<?= $waPhone ?>?text=<?= urlencode('Hello ' . $inq['name'] . ', thank you for contacting Global Trading.') ?>" target="_blank" class="badge bg-success text-white text-decoration-none" title="Chat on WhatsApp">
                                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= htmlspecialchars($inq['category'] ?? 'General') ?></span>
                                </td>
                                <td>
                                    <div class="text-dark mb-1" style="max-height: 48px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.35; font-size: 0.9rem;">
                                        <?= htmlspecialchars($inq['message']) ?>
                                    </div>
                                    <?php if (!empty($inq['admin_notes'])): ?>
                                        <small class="text-primary d-block mt-1">
                                            <i class="fa fa-sticky-note me-1"></i><strong>Note:</strong> <?= htmlspecialchars(substr($inq['admin_notes'], 0, 45)) ?>...
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($inq['status'] === 'new'): ?>
                                        <span class="badge bg-danger px-2 py-1"><i class="fa fa-bell me-1"></i> New</span>
                                    <?php elseif ($inq['status'] === 'contacted'): ?>
                                        <span class="badge bg-info text-dark px-2 py-1"><i class="fa fa-phone-alt me-1"></i> Contacted</span>
                                    <?php elseif ($inq['status'] === 'in_progress'): ?>
                                        <span class="badge bg-warning text-dark px-2 py-1"><i class="fa fa-spinner me-1"></i> In Progress</span>
                                    <?php elseif ($inq['status'] === 'closed'): ?>
                                        <span class="badge bg-success px-2 py-1"><i class="fa fa-check me-1"></i> Closed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted d-block"><?= date('d M Y', strtotime($inq['created_at'])) ?></small>
                                    <small class="text-muted text-opacity-75"><?= date('h:i A', strtotime($inq['created_at'])) ?></small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <!-- View / Edit Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#inquiryModal<?= $inq['id'] ?>" title="View Full Details">
                                            <i class="fa fa-eye me-1"></i> View / Update
                                        </button>

                                        <!-- Quick Status Dropdown -->
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle dropdown-toggle no-arrow px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Quick Status">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li><h6 class="dropdown-header">Quick Change Status</h6></li>
                                                <li>
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="quick_status">
                                                        <input type="hidden" name="id" value="<?= $inq['id'] ?>">
                                                        <input type="hidden" name="status" value="contacted">
                                                        <button type="submit" class="dropdown-item"><i class="fa fa-phone-alt text-info me-2"></i>Mark Contacted</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="quick_status">
                                                        <input type="hidden" name="id" value="<?= $inq['id'] ?>">
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="dropdown-item"><i class="fa fa-spinner text-warning me-2"></i>Mark In Progress</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="quick_status">
                                                        <input type="hidden" name="id" value="<?= $inq['id'] ?>">
                                                        <input type="hidden" name="status" value="closed">
                                                        <button type="submit" class="dropdown-item"><i class="fa fa-check text-success me-2"></i>Close Query</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="quick_status">
                                                        <input type="hidden" name="id" value="<?= $inq['id'] ?>">
                                                        <input type="hidden" name="status" value="new">
                                                        <button type="submit" class="dropdown-item"><i class="fa fa-undo text-danger me-2"></i>Reopen as New</button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry permanently?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?= $inq['id'] ?>">
                                                        <button type="submit" class="dropdown-item text-danger"><i class="fa fa-trash me-2"></i>Delete Inquiry</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</form>

<!-- Modal Details & Status Updater for Each Inquiry -->
<?php if (!empty($inquiries)): ?>
    <?php foreach ($inquiries as $inq): ?>
        <?php
        $cleanPhone = preg_replace('/[^0-9]/', '', $inq['phone'] ?? '');
        if (strlen($cleanPhone) === 11 && substr($cleanPhone, 0, 1) === '0') {
            $waPhone = '92' . substr($cleanPhone, 1);
        } else {
            $waPhone = $cleanPhone;
        }
        ?>
        <div class="modal fade" id="inquiryModal<?= $inq['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-light border-bottom p-4">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary rounded-pill">#INQ-<?= str_pad((string)$inq['id'], 5, '0', STR_PAD_LEFT) ?></span>
                                <h5 class="modal-title fw-bold text-dark mb-0">Inquiry from <?= htmlspecialchars($inq['name']) ?></h5>
                            </div>
                            <small class="text-muted"><i class="fa fa-clock me-1"></i>Submitted on <?= date('d M Y \a\t h:i A', strtotime($inq['created_at'])) ?></small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="update_details">
                        <input type="hidden" name="id" value="<?= $inq['id'] ?>">

                        <div class="modal-body p-4">
                            <!-- Client Info Cards -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">Client Name</small>
                                        <span class="fw-bold text-dark fs-6"><?= htmlspecialchars($inq['name']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">Email</small>
                                        <a href="mailto:<?= htmlspecialchars($inq['email']) ?>" class="fw-bold text-primary text-decoration-none" style="word-break: break-all;">
                                            <i class="fa fa-envelope me-1"></i><?= htmlspecialchars($inq['email']) ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">Phone</small>
                                        <?php if (!empty($inq['phone'])): ?>
                                            <a href="tel:<?= htmlspecialchars($inq['phone']) ?>" class="fw-bold text-dark text-decoration-none d-block">
                                                <i class="fa fa-phone text-success me-1"></i><?= htmlspecialchars($inq['phone']) ?>
                                            </a>
                                            <?php if (!empty($waPhone)): ?>
                                                <a href="https://wa.me/<?= $waPhone ?>" target="_blank" class="btn btn-xs btn-outline-success rounded-pill mt-1 py-0 px-2 small">
                                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not Provided</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Category -->
                            <div class="mb-3">
                                <strong class="d-block text-muted small text-uppercase mb-1">Inquiry Category:</strong>
                                <span class="badge bg-secondary px-3 py-2 fs-6 rounded-pill"><?= htmlspecialchars($inq['category'] ?? 'General') ?></span>
                            </div>

                            <!-- Full Message -->
                            <div class="mb-4">
                                <strong class="d-block text-muted small text-uppercase mb-1">Customer Message / Requirements:</strong>
                                <div class="p-3 bg-light rounded-3 border text-dark" style="white-space: pre-line; line-height: 1.6; font-size: 0.95rem;">
                                    <?= htmlspecialchars($inq['message']) ?>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Status & Follow-up Section -->
                            <h6 class="fw-bold text-dark mb-3"><i class="fa fa-tasks text-primary me-2"></i>Update Inquiry Status &amp; Admin Notes</h6>
                            
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Current Inquiry Status</label>
                                    <select name="status" class="form-select bg-light">
                                        <option value="new" <?= $inq['status'] === 'new' ? 'selected' : '' ?>>🔴 New / Unread (Requires Action)</option>
                                        <option value="contacted" <?= $inq['status'] === 'contacted' ? 'selected' : '' ?>>📞 Contacted (Called / Emailed)</option>
                                        <option value="in_progress" <?= $inq['status'] === 'in_progress' ? 'selected' : '' ?>>⏳ In Progress (Quotation / Negotiation)</option>
                                        <option value="closed" <?= $inq['status'] === 'closed' ? 'selected' : '' ?>>✅ Closed / Resolved (Deal Completed or Archived)</option>
                                    </select>
                                </div>

                                <div class="col-md-7">
                                    <label class="form-label fw-semibold">Internal Notes / Follow-up Record</label>
                                    <textarea name="admin_notes" class="form-control bg-light" rows="3" placeholder="Add follow-up notes, quotation details, call outcome, etc..."><?= htmlspecialchars($inq['admin_notes'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light p-3 border-top d-flex justify-content-between">
                            <div>
                                <small class="text-muted">Last updated: <?= !empty($inq['updated_at']) ? date('d M Y, h:i A', strtotime($inq['updated_at'])) : date('d M Y, h:i A', strtotime($inq['created_at'])) ?></small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <i class="fa fa-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
function toggleSelectAll(master) {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(cb => cb.checked = master.checked);
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
