<?php
// admin/delete-product.php - Product Deletion Handler
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
requireAdmin();

$pdo = getDBConnection();
$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // Optionally delete image from disk if not default
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();

    if ($img && $img !== 'default-product.jpg') {
        $imgPath = __DIR__ . '/../uploads/products/' . $img;
        if (file_exists($imgPath)) {
            @unlink($imgPath);
        }
    }

    $delStmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $delStmt->execute([$id]);
}

header("Location: products.php");
exit;
?>
