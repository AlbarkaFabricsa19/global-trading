<?php
// db_setup.php - Auto Database & Initial Catalog Data Seeder
require_once __DIR__ . '/config/db.php';

// First, if MySQL is running, attempt to create database if not exists
try {
    $mysqlTest = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $mysqlTest->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
} catch (PDOException $e) {
    // MySQL not reachable or credentials missing; will use SQLite fallback in getDBConnection()
}

$pdo = getDBConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

if ($driver === 'mysql') {
    // MySQL Table Queries
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) NOT NULL UNIQUE,
            section_type VARCHAR(50) NOT NULL DEFAULT 'stitching',
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT NOT NULL,
            name VARCHAR(150) NOT NULL,
            slug VARCHAR(150) NOT NULL,
            pricing_tag VARCHAR(100) DEFAULT 'COMPETITIVE MARKET RATES',
            short_desc TEXT,
            full_desc TEXT,
            items_list TEXT,
            image VARCHAR(255) NOT NULL DEFAULT 'default-product.jpg',
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS trusted_customers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            logo_image VARCHAR(255) DEFAULT NULL,
            alt_text VARCHAR(255) DEFAULT '',
            sort_order INT DEFAULT 0,
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
} else {
    // SQLite Table Queries
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            email TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            section_type TEXT NOT NULL DEFAULT 'stitching',
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            slug TEXT NOT NULL,
            pricing_tag TEXT DEFAULT 'COMPETITIVE MARKET RATES',
            short_desc TEXT,
            full_desc TEXT,
            items_list TEXT,
            image TEXT NOT NULL DEFAULT 'default-product.jpg',
            status INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        );
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS trusted_customers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            logo_image TEXT DEFAULT NULL,
            alt_text TEXT DEFAULT '',
            sort_order INTEGER DEFAULT 0,
            status INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");
}

// Ensure default Admin user exists (from .env or defaults)
$adminUser = env('ADMIN_USERNAME', 'admin');
$adminPass = env('ADMIN_PASSWORD', 'admin123');
$adminEmail = env('ADMIN_EMAIL', 'globaltradin@gmail.com');

$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
$stmt->execute([$adminUser]);
if ($stmt->fetchColumn() == 0) {
    $hashedPass = password_hash($adminPass, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute([$adminUser, $hashedPass, $adminEmail]);
}

// Seed Initial Catalog Categories if empty
$stmt = $pdo->prepare("SELECT COUNT(*) FROM categories");
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    $categories = [
        ['name' => 'Tapes', 'slug' => 'tapes', 'section_type' => 'stitching', 'description' => 'Industrial and textile masking, PVC, and dyed tapes.'],
        ['name' => 'Elastic', 'slug' => 'elastic', 'section_type' => 'stitching', 'description' => 'Needleloom elastic, plain elastic, and specialty varieties.'],
        ['name' => 'Buttons', 'slug' => 'buttons', 'section_type' => 'stitching', 'description' => 'Sea shell, shell, plastic, and tich buttons.'],
        ['name' => 'Threads & Cords', 'slug' => 'threads-cords', 'section_type' => 'stitching', 'description' => 'Sateen dori, tag dori, and local & imported threads.'],
        ['name' => 'Raw Materials', 'slug' => 'raw-materials', 'section_type' => 'stitching', 'description' => 'Direct import of high-grade raw materials.'],
        ['name' => 'Pipes', 'slug' => 'pipes', 'section_type' => 'mechanical_electrical', 'description' => 'PVC, GI, Steel, HDPE, and flexible pipes.'],
        ['name' => 'Sheets', 'slug' => 'sheets', 'section_type' => 'mechanical_electrical', 'description' => 'Steel, aluminum, galvanized, and fiber sheets.'],
        ['name' => 'Angles', 'slug' => 'angles', 'section_type' => 'mechanical_electrical', 'description' => 'Mild steel and stainless steel angles in all sizes.'],
        ['name' => 'Channels', 'slug' => 'channels', 'section_type' => 'mechanical_electrical', 'description' => 'C-Channels, U-Channels, and steel structural channels.'],
        ['name' => 'Additional Electrical & Fittings', 'slug' => 'additional-fittings', 'section_type' => 'mechanical_electrical', 'description' => 'Fasteners, electrical cables, switches, breakers, trunking, pipe fittings.']
    ];

    $catStmt = $pdo->prepare("INSERT INTO categories (name, slug, section_type, description) VALUES (?, ?, ?, ?)");
    foreach ($categories as $cat) {
        $catStmt->execute([$cat['name'], $cat['slug'], $cat['section_type'], $cat['description']]);
    }
}

// Seed Initial Catalog Products if empty
$stmt = $pdo->prepare("SELECT COUNT(*) FROM products");
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    // Map category names to IDs
    $catMap = [];
    $res = $pdo->query("SELECT id, name FROM categories")->fetchAll();
    foreach ($res as $r) {
        $catMap[$r['name']] = $r['id'];
    }

    $products = [
        [
            'category_id' => $catMap['Tapes'] ?? 1,
            'name' => 'Industrial Tapes Collection',
            'slug' => 'industrial-tapes-collection',
            'pricing_tag' => 'COMPETITIVE MARKET RATES',
            'short_desc' => 'High quality Masking Tape, PVC Tape, and Dyed Tapes for industrial and garment stitching applications.',
            'full_desc' => 'Our industrial tape collection features high-adhesion masking tapes, electrical-grade PVC tapes, and custom dyed tapes suitable for garment manufacturing and packaging.',
            'items_list' => json_encode(['Masking Tape', 'PVC Tape', 'Dyed Tape', 'High Adhesion Rolls', 'Heat Resistant Tapes']),
            'image' => 'tape-collection.jpg'
        ],
        [
            'category_id' => $catMap['Elastic'] ?? 2,
            'name' => 'Premium Garment Elastic Range',
            'slug' => 'premium-garment-elastic-range',
            'pricing_tag' => 'DIRECT IMPORT RATES',
            'short_desc' => 'Needleloom elastic, plain woven elastic, and customized elastic bands for apparel.',
            'full_desc' => 'Comprehensive range of elastic bands designed for apparel, sportswear, and industrial applications. Provides high stretch recovery and long-lasting durability.',
            'items_list' => json_encode(['Needleloom Elastic', 'Plain Elastic', 'Woven Elastic', 'Knitted Elastic', 'Custom Colored Elastic']),
            'image' => 'elastic-range.jpg'
        ],
        [
            'category_id' => $catMap['Buttons'] ?? 3,
            'name' => 'Fashion & Industrial Buttons Variety',
            'slug' => 'fashion-industrial-buttons-variety',
            'pricing_tag' => 'BULK WHOLESALE RATES',
            'short_desc' => 'Sea Shell Buttons, Natural Shell Buttons, Premium Plastic Buttons, and Tich Buttons.',
            'full_desc' => 'Extensive button collection suitable for shirt manufacturing, jeans, jackets, and haute couture. Available in natural seashell, polished shell, durable plastic, and snap tich buttons.',
            'items_list' => json_encode(['Sea Shell Buttons', 'Shell Buttons', 'Plastic Buttons', 'Tich Buttons', 'Metal Snap Buttons']),
            'image' => 'buttons-collection.jpg'
        ],
        [
            'category_id' => $catMap['Threads & Cords'] ?? 4,
            'name' => 'High Strength Threads & Cords',
            'slug' => 'high-strength-threads-cords',
            'pricing_tag' => 'COMPETITIVE MARKET RATES',
            'short_desc' => 'Sateen Dori, Tag Dori, and local & imported stitching thread varieties.',
            'full_desc' => 'Premium quality cords including smooth Sateen Dori, durable Tag Dori for brand tags, and heavy-duty stitching threads in diverse colors and counts.',
            'items_list' => json_encode(['Sateen Dori', 'Tag Dori', 'Local Thread Varieties', 'Imported Thread Spools', 'Braided Cords']),
            'image' => 'threads-cords.jpg'
        ],
        [
            'category_id' => $catMap['Raw Materials'] ?? 5,
            'name' => 'Imported High-Grade Raw Materials',
            'slug' => 'imported-high-grade-raw-materials',
            'pricing_tag' => 'OWN IMPORT PRICES',
            'short_desc' => 'Direct import of high-quality raw materials for textile and manufacturing units.',
            'full_desc' => 'Directly imported raw materials sourced from international suppliers to ensure high consistency, superior yarn/material quality, and cost advantages.',
            'items_list' => json_encode(['Polyester Yarns', 'Raw Rubber Threads', 'Base Polymer Foams', 'Chemical Accessories']),
            'image' => 'raw-materials.jpg'
        ],
        [
            'category_id' => $catMap['Pipes'] ?? 6,
            'name' => 'Industrial & Domestic Pipe Solutions',
            'slug' => 'industrial-domestic-pipe-solutions',
            'pricing_tag' => 'RELIABLE PERFORMANCE',
            'short_desc' => 'PVC Pipes, GI Pipes, Steel Pipes, HDPE Pipes, and Flexible Corrugated Pipes.',
            'full_desc' => 'Heavy-duty piping systems for plumbing, industrial fluid transport, structural framing, and cable conduit installations. Built for pressure resistance and longevity.',
            'items_list' => json_encode(['PVC Pipes', 'GI Pipes (Galvanized Iron)', 'Steel Pipes', 'HDPE Pipes', 'Flexible Corrugated Pipes']),
            'image' => 'pipes-range.jpg'
        ],
        [
            'category_id' => $catMap['Sheets'] ?? 7,
            'name' => 'Metal & Composite Sheets',
            'slug' => 'metal-composite-sheets',
            'pricing_tag' => 'FACTORY DIRECT RATES',
            'short_desc' => 'Steel Sheets, Aluminum Sheets, Galvanized Sheets, and Fiber Sheets.',
            'full_desc' => 'High-grade metal sheets including rust-resistant galvanized sheets, lightweight aluminum plates, structural steel sheets, and weather-resistant fiber sheets.',
            'items_list' => json_encode(['Steel Sheets', 'Aluminum Sheets', 'Galvanized Sheets', 'Fiber Sheets', 'Checkered Plates']),
            'image' => 'metal-sheets.jpg'
        ],
        [
            'category_id' => $catMap['Angles'] ?? 8,
            'name' => 'Structural Mild & Stainless Steel Angles',
            'slug' => 'structural-mild-stainless-steel-angles',
            'pricing_tag' => 'ALL SIZES AVAILABLE',
            'short_desc' => 'Mild Steel Angles, Stainless Steel Angles in all custom sizes.',
            'full_desc' => 'Precision-milled L-angles engineered for construction, structural framing, machinery supports, and fabrication works.',
            'items_list' => json_encode(['Mild Steel Angles', 'Stainless Steel Angles (304/316)', 'Equal Angles', 'Unequal Angles']),
            'image' => 'steel-angles.jpg'
        ],
        [
            'category_id' => $catMap['Channels'] ?? 9,
            'name' => 'Heavy Structural Steel Channels',
            'slug' => 'heavy-structural-steel-channels',
            'pricing_tag' => 'HEAVY DUTY GRADE',
            'short_desc' => 'C-Channels, U-Channels, and Steel structural channels.',
            'full_desc' => 'Cold-formed and hot-rolled structural steel C-Channels and U-Channels designed to withstand heavy structural loads in industrial buildings.',
            'items_list' => json_encode(['C-Channels', 'U-Channels', 'Steel Channels', 'Custom Length Channels']),
            'image' => 'steel-channels.jpg'
        ],
        [
            'category_id' => $catMap['Additional Electrical & Fittings'] ?? 10,
            'name' => 'Electrical Wiring & Hardware Fittings',
            'slug' => 'electrical-wiring-hardware-fittings',
            'pricing_tag' => 'COMPLETE SOLUTION',
            'short_desc' => 'Fasteners (Nuts, Bolts), Electrical Wiring, Switches, Circuit Breakers, Conduits, Pipe Fittings.',
            'full_desc' => 'Comprehensive inventory of industrial electrical fittings, circuit protection breakers, high-voltage wiring, and mechanical fasteners.',
            'items_list' => json_encode(['Fasteners (Nuts, Bolts, Screws)', 'Electrical Wiring & Cables', 'Switches & Sockets', 'Circuit Breakers', 'Conduits & Trunking', 'Pipe Fittings (Elbows, Tees, Reducers)']),
            'image' => 'electrical-fittings.jpg'
        ]
    ];

    $prodStmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, pricing_tag, short_desc, full_desc, items_list, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($products as $p) {
        $prodStmt->execute([
            $p['category_id'],
            $p['name'],
            $p['slug'],
            $p['pricing_tag'],
            $p['short_desc'],
            $p['full_desc'],
            $p['items_list'],
            $p['image']
        ]);
    }
}

// Make sure upload directory exists
$uploadDir = __DIR__ . '/uploads/products/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Make sure trusted customers upload directory exists
$customersUploadDir = __DIR__ . '/uploads/customers/';
if (!file_exists($customersUploadDir)) {
    mkdir($customersUploadDir, 0777, true);
}

// Seed Trusted Customers if empty
$stmt = $pdo->prepare("SELECT COUNT(*) FROM trusted_customers");
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    $customers = [
        ['name' => 'ETHNIC',                    'alt_text' => 'Ethnic Fashion & Retail Brand Logo',              'sort_order' => 1],
        ['name' => 'Outfitters',                'alt_text' => 'Outfitters Apparel & Lifestyle Logo',             'sort_order' => 2],
        ['name' => 'Loftex Limited',            'alt_text' => 'Loftex Limited Textile Manufacturing Logo',       'sort_order' => 3],
        ['name' => 'Al Barka Fabrics',          'alt_text' => 'Al Barka Fabrics Private Limited Logo',           'sort_order' => 4],
        ['name' => 'AK Fabrics Pvt Ltd',        'alt_text' => 'AK Fabrics Private Limited Logo',                'sort_order' => 5],
        ['name' => 'SolarX',                    'alt_text' => 'SolarX Renewable Energy Logo',                   'sort_order' => 6],
        ['name' => 'University of Faisalabad',  'alt_text' => 'The University of Faisalabad Logo',              'sort_order' => 7],
        ['name' => 'Diamond Styrolon Industries','alt_text' => 'Diamond Styrolon Industries Logo',              'sort_order' => 8],
        ['name' => 'Kay & Emms Global',         'alt_text' => 'Kay & Emms Global Logo',                         'sort_order' => 9],
        ['name' => 'Diamond Clothing Ind',      'alt_text' => 'Diamond Clothing Ind (PVT) Ltd Logo',            'sort_order' => 10],
    ];

    $custStmt = $pdo->prepare("INSERT INTO trusted_customers (name, logo_image, alt_text, sort_order, status) VALUES (?, NULL, ?, ?, 1)");
    foreach ($customers as $c) {
        $custStmt->execute([$c['name'], $c['alt_text'], $c['sort_order']]);
    }
}

// If invoked directly via browser/command line, display status message
if (basename($_SERVER['PHP_SELF']) === 'db_setup.php') {
    echo "<h2>Global Trading Database &amp; Seeder executed successfully!</h2>";
    echo "<p>Default Admin User: <strong>admin</strong> | Password: <strong>admin123</strong></p>";
    echo "<p><a href='index.php'>Go to Website Homepage</a> | <a href='admin/login.php'>Go to Admin Panel</a></p>";
}
?>
