<?php
// admin/login.php - Admin Panel Login
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';

// Auto-run DB setup if tables don't exist
require_once __DIR__ . '/../db_setup.php';

if (isAdminLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Global Trading</title>
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="../images/favicon_io/site.webmanifest">
    
    <!-- Google Fonts (DM Sans & Sora) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Sora:wght@100..800&display=swap" rel="stylesheet">
    
    <!-- Local CSS Files (No External CDNs) -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    
    <style>
        .login-body{
            overflow: hidden;
        }
        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 15px;
        }
        .login-card {
            border-radius: 18px;
            box-shadow: 0 20px 45px rgba(2, 23, 54, 0.35);
        }
        .login-header {
            background: #021736;
            border-bottom: 3px solid #00607a;
            padding: 32px 24px;
            text-align: center;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }
        .card-body{
            border-bottom-left-radius: 18px;
            border-bottom-right-radius: 18px;
        }
    </style>
</head>
<body class="login-body">

<div class="login-wrapper">
    <div class="card login-card">
        <div class="login-header">
            <img src="../images/logo-w.png" alt="Global Trading" style="max-height: 42px;" class="mb-2">
            <h4 class="mb-0 text-white fw-bold">Admin Portal</h4>
            <small class="text-white-50">Global Trading Catalog Management</small>
        </div>
        <div class="card-body p-4 p-md-5 bg-white">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 small mb-4" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="fa fa-user text-primary me-2"></i>Username</label>
                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Enter username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-semibold"><i class="fa fa-lock text-primary me-2"></i>Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3">
                    Sign In <i class="fa fa-sign-in-alt ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <a href="../index.php" class="text-decoration-none text-muted small">
                    <i class="fa fa-arrow-left me-1"></i> Back to Main Website
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Local Bootstrap Bundle JS -->
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
