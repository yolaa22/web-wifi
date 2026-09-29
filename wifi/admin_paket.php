<?php
session_start();

// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "wifinet_db";
$port = 3306;

$koneksi = mysqli_connect($host, $username, $password, $database, $port);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Inisialisasi variabel
$pesan = "";
$pesan_error = "";
$paket_edit = null;

// Tangani form tambah/update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_paket = mysqli_real_escape_string($koneksi, $_POST['nama_paket']);
    $kecepatan = mysqli_real_escape_string($koneksi, $_POST['kecepatan']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $fitur1 = mysqli_real_escape_string($koneksi, $_POST['fitur1']);
    $fitur2 = mysqli_real_escape_string($koneksi, $_POST['fitur2']);
    $fitur3 = mysqli_real_escape_string($koneksi, $_POST['fitur3']);
    $tombol_text = mysqli_real_escape_string($koneksi, $_POST['tombol_text']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    
    if (isset($_POST['tambah'])) {
        // Query insert
        $query = "INSERT INTO paket_layanan (nama_paket, kecepatan, harga, fitur1, fitur2, fitur3, tombol_text, kategori) 
                  VALUES ('$nama_paket', '$kecepatan', '$harga', '$fitur1', '$fitur2', '$fitur3', '$tombol_text', '$kategori')";
        
        if (mysqli_query($koneksi, $query)) {
            $pesan = "Paket berhasil ditambahkan!";
            $_SESSION['pesan'] = $pesan;
            header("Location: admin_paket.php");
            exit();
        } else {
            $pesan_error = "Gagal menambahkan paket: " . mysqli_error($koneksi);
        }
    }
    
    if (isset($_POST['update'])) {
        $id = mysqli_real_escape_string($koneksi, $_POST['id']);
        
        // Query update
        $query = "UPDATE paket_layanan SET 
                  nama_paket = '$nama_paket',
                  kecepatan = '$kecepatan',
                  harga = '$harga',
                  fitur1 = '$fitur1',
                  fitur2 = '$fitur2',
                  fitur3 = '$fitur3',
                  tombol_text = '$tombol_text',
                  kategori = '$kategori',
                  updated_at = NOW()
                  WHERE id = $id";
        
        if (mysqli_query($koneksi, $query)) {
            $pesan = "Paket berhasil diperbarui!";
            $_SESSION['pesan'] = $pesan;
            header("Location: admin_paket.php");
            exit();
        } else {
            $pesan_error = "Gagal memperbarui paket: " . mysqli_error($koneksi);
        }
    }
}

// Tangani hapus paket
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $query = "DELETE FROM paket_layanan WHERE id = $id";
    
    if (mysqli_query($koneksi, $query)) {
        $pesan = "Paket berhasil dihapus!";
        $_SESSION['pesan'] = $pesan;
        header("Location: admin_paket.php");
        exit();
    } else {
        $pesan_error = "Gagal menghapus paket: " . mysqli_error($koneksi);
    }
}

// Ambil data untuk edit
if (isset($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $query_edit = "SELECT * FROM paket_layanan WHERE id = $id_edit";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $paket_edit = mysqli_fetch_assoc($result_edit);
}

// Ambil semua data paket
$query_paket = "SELECT * FROM paket_layanan ORDER BY kategori, CAST(SUBSTRING_INDEX(harga, ' ', 1) AS UNSIGNED)";
$result_paket = mysqli_query($koneksi, $query_paket);

// Hitung statistik
$query_count = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN kategori = 'Home & Personal' THEN 1 ELSE 0 END) as home,
    SUM(CASE WHEN kategori = 'Business' THEN 1 ELSE 0 END) as business,
    SUM(CASE WHEN kategori = 'Enterprise' THEN 1 ELSE 0 END) as enterprise
    FROM paket_layanan";
$result_count = mysqli_query($koneksi, $query_count);
$stats = mysqli_fetch_assoc($result_count);

// Cek pesan dari session
if (isset($_SESSION['pesan'])) {
    $pesan = $_SESSION['pesan'];
    unset($_SESSION['pesan']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paket - WiFiNet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Container */
        .container {
            display: flex;
            width: 100%;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 250px;
            background: linear-gradient(135deg, #2c5aa0 0%, #1a3d6b 100%);
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .admin-logo {
            padding: 0 20px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: block;
        }

        .admin-logo h2 {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .admin-logo span {
            color: #4dabf7;
        }

        .admin-menu ul {
            list-style: none;
            padding: 20px 0;
        }

        .admin-menu li {
            margin-bottom: 5px;
        }

        .admin-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            gap: 12px;
        }

        .admin-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .admin-menu a.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border-left: 4px solid #4dabf7;
        }

        .admin-menu i {
            width: 20px;
            text-align: center;
        }

        .admin-logout {
            margin-top: auto;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-logout a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s;
            gap: 12px;
        }

        .admin-logout a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* Main Content */
        .main-content-wrapper {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }

        /* Header */
        .content-header {
            background-color: white;
            border-radius: 10px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-header h1 {
            color: #2c5aa0;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .stat-icon.total {
            background-color: #e3eeff;
            color: #2c5aa0;
        }

        .stat-icon.home {
            background-color: #e8f5e9;
            color: #388e3c;
        }

        .stat-icon.business {
            background-color: #fff3e0;
            color: #f57c00;
        }

        .stat-icon.enterprise {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .stat-info h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #666;
            font-size: 0.9rem;
        }

        /* Alert */
        #alertContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
            max-width: 400px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideInRight 0.3s;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }

        .alert-error {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #f44336;
        }

        /* Main Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        @media (max-width: 1100px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Form Container */
        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .form-container h2 {
            color: #2c5aa0;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c5aa0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Table Container */
        .table-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-container h2 {
            color: #2c5aa0;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c5aa0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
            line-height: 1.5;
        }

        .form-control:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            justify-content: center;
        }

        .btn-primary {
            background-color: #28a745;
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .btn-view {
            background-color: #2c5aa0;
            color: white;
            text-decoration: none;
        }

        .btn-view:hover {
            background-color: #1a3d6b;
            transform: translateY(-2px);
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table th {
            background-color: #f8fafc;
            color: #2c5aa0;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e1e8f0;
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #e1e8f0;
            vertical-align: middle;
        }

        .data-table tr:hover {
            background-color: #f9fafb;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-edit, .btn-delete {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #e3eeff;
            color: #2c5aa0;
        }

        .btn-edit:hover {
            background-color: #2c5aa0;
            color: white;
        }

        .btn-delete {
            background-color: #ffebee;
            color: #d32f2f;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
            color: white;
        }

        /* Preview Section */
        .preview-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }

        .preview-container h2 {
            color: #2c5aa0;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c5aa0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .preview-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }

        .preview-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .preview-header h4 {
            color: #2c5aa0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .preview-speed {
            background-color: #2c5aa0;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .preview-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .preview-features {
            list-style: none;
            margin-bottom: 20px;
        }

        .preview-features li {
            padding: 8px 0;
            color: #555;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border-bottom: 1px solid #eee;
        }

        .preview-features li:last-child {
            border-bottom: none;
        }

        .preview-features li i {
            color: #28a745;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .preview-button {
            background-color: #2c5aa0;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1rem;
        }

        .preview-button:hover {
            background-color: #1a3d6b;
        }

        .preview-footer {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 0.85rem;
            color: #777;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        /* Search and Filter */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-box {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            width: 300px;
            font-size: 0.9rem;
        }

        .filter-select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: white;
            font-size: 0.9rem;
        }

        /* Category Badge */
        .category-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .category-badge.home { background-color: #e3eeff; color: #2c5aa0; }
        .category-badge.business { background-color: #e8f5e9; color: #388e3c; }
        .category-badge.enterprise { background-color: #fff3e0; color: #f57c00; }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 70px;
            }
            
            .admin-sidebar span {
                display: none;
            }
            
            .main-content-wrapper {
                margin-left: 70px;
            }
            
            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .preview-grid {
                grid-template-columns: 1fr;
            }
            
            .search-box {
                width: 100%;
            }
            
            .table-controls {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-logo">
            <i class="fas fa-wifi logo-icon"></i>
            <h2>WiFiNet<span>Admin</span></h2>
        </div>
        
        <div class="admin-menu">
            <ul>
                <li><a href="admin-dashboard.html"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="admin_paket.php" class="active"><i class="fas fa-wifi"></i> <span>Layanan</span></a></li>
                <li><a href="admin-galeri.html"><i class="fas fa-images"></i> <span>Galeri Perusahaan</span></a></li>
                <li><a href="admin-tim.html"><i class="fas fa-users"></i> <span>Galeri Tim</span></a></li>
                <li><a href="admin_kontak_info.php"><i class="fas fa-address-card"></i> <span>Kontak</span></a></li>
            </ul>
        </div>
        
        <div class="admin-logout">
            <a href="index.html"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content-wrapper">
        <div class="content-header">
            <h1><i class="fas fa-wifi"></i> Kelola Paket Layanan</h1>
            <a href="services.php" target="_blank" class="btn btn-view">
                <i class="fas fa-eye"></i> Lihat Halaman Layanan
            </a>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['total'] ?? 0; ?></h3>
                    <p>Total Paket</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon home">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['home'] ?? 0; ?></h3>
                    <p>Home & Personal</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon business">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['business'] ?? 0; ?></h3>
                    <p>Business</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon enterprise">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['enterprise'] ?? 0; ?></h3>
                    <p>Enterprise</p>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <div id="alertContainer">
            <?php if ($pesan): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $pesan; ?>
                </div>
            <?php endif; ?>

            <?php if ($pesan_error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $pesan_error; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Main Grid -->
        <div class="content-grid">
            <!-- Form -->
            <div class="form-container">
                <h2><i class="fas fa-edit"></i> <?php echo $paket_edit ? 'Edit Paket Layanan' : 'Tambah Paket Baru'; ?></h2>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <?php if ($paket_edit): ?>
                        <input type="hidden" name="id" value="<?php echo $paket_edit['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="nama_paket"><i class="fas fa-tag"></i> Nama Paket</label>
                        <input type="text" id="nama_paket" name="nama_paket" class="form-control" 
                               value="<?php echo $paket_edit ? htmlspecialchars($paket_edit['nama_paket']) : ''; ?>" 
                               placeholder="Contoh: Paket Home" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="kecepatan"><i class="fas fa-tachometer-alt"></i> Kecepatan Internet</label>
                            <input type="text" id="kecepatan" name="kecepatan" class="form-control" 
                                   value="<?php echo $paket_edit ? htmlspecialchars($paket_edit['kecepatan']) : ''; ?>" 
                                   placeholder="Contoh: 10-25 Mbps" required>
                        </div>
                        <div class="form-group">
                            <label for="harga"><i class="fas fa-money-bill-wave"></i> Harga</label>
                            <input type="text" id="harga" name="harga" class="form-control" 
                                   value="<?php echo $paket_edit ? htmlspecialchars($paket_edit['harga']) : ''; ?>" 
                                   placeholder="Contoh: Rp 150.000" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="kategori"><i class="fas fa-filter"></i> Kategori Paket</label>
                        <select id="kategori" name="kategori" class="form-control" required>
                            <option value="Home & Personal" <?php echo ($paket_edit && $paket_edit['kategori'] == 'Home & Personal') ? 'selected' : ''; ?>>Home & Personal</option>
                            <option value="Business" <?php echo ($paket_edit && $paket_edit['kategori'] == 'Business') ? 'selected' : ''; ?>>Business</option>
                            <option value="Enterprise" <?php echo ($paket_edit && $paket_edit['kategori'] == 'Enterprise') ? 'selected' : ''; ?>>Enterprise</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-list-check"></i> Fitur Paket</label>
                        <input type="text" name="fitur1" class="form-control" placeholder="Fitur 1" 
                               value="<?php echo $paket_edit ? htmlspecialchars($paket_edit['fitur1']) : ''; ?>" required>
                        <input type="text" name="fitur2" class="form-control" placeholder="Fitur 2" 
                               value="<?php echo $paket_edit ? htmlspecialchars($paket_edit['fitur2']) : ''; ?>" required style="margin-top: 10px;">
                        <input type="text" name="fitur3" class="form-control" placeholder="Fitur 3" 
                               value="<?php echo $paket_edit ? htmlspecialchars($paket_edit['fitur3']) : ''; ?>" required style="margin-top: 10px;">
                    </div>
                    
                    <div class="form-group">
                        <label for="tombol_text"><i class="fas fa-button"></i> Teks Tombol</label>
                        <select id="tombol_text" name="tombol_text" class="form-control" required>
                            <option value="Pesan Sekarang" <?php echo ($paket_edit && $paket_edit['tombol_text'] == 'Pesan Sekarang') ? 'selected' : ''; ?>>Pesan Sekarang</option>
                            <option value="Hubungi Kami" <?php echo ($paket_edit && $paket_edit['tombol_text'] == 'Hubungi Kami') ? 'selected' : ''; ?>>Hubungi Kami</option>
                            <option value="Daftar Sekarang" <?php echo ($paket_edit && $paket_edit['tombol_text'] == 'Daftar Sekarang') ? 'selected' : ''; ?>>Daftar Sekarang</option>
                        </select>
                    </div>
                    
                    <div class="btn-group">
                        <?php if ($paket_edit): ?>
                            <button type="submit" name="update" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Paket
                            </button>
                            <a href="admin_paket.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        <?php else: ?>
                            <button type="submit" name="tambah" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Paket
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset Form
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-container">
                <div class="table-controls">
                    <input type="text" class="search-box" id="searchInput" placeholder="Cari paket...">
                    <select class="filter-select" id="categoryFilter">
                        <option value="all">Semua Kategori</option>
                        <option value="Home & Personal">Home & Personal</option>
                        <option value="Business">Business</option>
                        <option value="Enterprise">Enterprise</option>
                    </select>
                </div>
                
                <table class="data-table" id="packageTable">
                    <thead>
                        <tr>
                            <th>Nama Paket</th>
                            <th>Kategori</th>
                            <th>Kecepatan</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="packageTableBody">
                        <?php if (mysqli_num_rows($result_paket) > 0): ?>
                            <?php mysqli_data_seek($result_paket, 0); ?>
                            <?php while ($row = mysqli_fetch_assoc($result_paket)): ?>
                                <tr>
                                    <td>
                                        <strong style="display: block; margin-bottom: 5px;"><?php echo htmlspecialchars($row['nama_paket']); ?></strong>
                                        <small style="color: #666; font-size: 0.9rem;">ID: <?php echo $row['id']; ?></small>
                                    </td>
                                    <td>
                                        <span class="category-badge <?php echo strtolower(explode(' ', $row['kategori'])[0]); ?>">
                                            <?php echo htmlspecialchars($row['kategori']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['kecepatan']); ?></td>
                                    <td><?php echo htmlspecialchars($row['harga']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="?edit=<?php echo $row['id']; ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="?hapus=<?php echo $row['id']; ?>" 
                                               class="btn-delete" 
                                               onclick="return confirm('Yakin ingin menghapus paket <?php echo addslashes($row['nama_paket']); ?>?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h3>Tidak ada data paket</h3>
                                        <p>Belum ada paket layanan yang ditambahkan</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Preview -->
        <?php if (mysqli_num_rows($result_paket) > 0): ?>
            <div class="preview-container">
                <h2><i class="fas fa-eye"></i> Preview Paket Layanan</h2>
                <div class="preview-grid">
                    <?php mysqli_data_seek($result_paket, 0); ?>
                    <?php while ($row = mysqli_fetch_assoc($result_paket)): ?>
                        <div class="preview-card">
                            <div class="preview-header">
                                <h4><?php echo htmlspecialchars($row['nama_paket']); ?></h4>
                                <span class="preview-speed"><?php echo htmlspecialchars($row['kecepatan']); ?></span>
                            </div>
                            <div class="preview-price"><?php echo htmlspecialchars($row['harga']); ?></div>
                            <ul class="preview-features">
                                <li><i class="fas fa-check"></i> <?php echo htmlspecialchars($row['fitur1']); ?></li>
                                <li><i class="fas fa-check"></i> <?php echo htmlspecialchars($row['fitur2']); ?></li>
                                <li><i class="fas fa-check"></i> <?php echo htmlspecialchars($row['fitur3']); ?></li>
                            </ul>
                            <button class="preview-button"><?php echo htmlspecialchars($row['tombol_text']); ?></button>
                            <div class="preview-footer">
                                <i class="fas fa-clock"></i>
                                <span>Diperbarui: <?php echo date('d-m-Y H:i', strtotime($row['updated_at'])); ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <script>
        // Auto-hide pesan sukses setelah 5 detik
        setTimeout(function() {
            const alertSuccess = document.querySelector('.alert-success');
            if (alertSuccess) {
                alertSuccess.style.opacity = '0';
                alertSuccess.style.transition = 'opacity 0.5s';
                setTimeout(() => alertSuccess.remove(), 500);
            }
        }, 5000);
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#packageTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Filter functionality
        document.getElementById('categoryFilter').addEventListener('change', function() {
            const selectedCategory = this.value;
            const rows = document.querySelectorAll('#packageTableBody tr');
            
            rows.forEach(row => {
                if (selectedCategory === 'all') {
                    row.style.display = '';
                    return;
                }
                
                const categoryBadge = row.querySelector('.category-badge');
                if (categoryBadge && categoryBadge.textContent === selectedCategory) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Preview real-time
        const formInputs = document.querySelectorAll('#nama_paket, #kecepatan, #harga, [name="fitur1"], [name="fitur2"], [name="fitur3"]');
        const previewCards = document.querySelectorAll('.preview-card');
        
        // Update first preview card when typing in form
        if (previewCards.length > 0 && formInputs.length > 0) {
            const firstCard = previewCards[0];
            
            formInputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.id === 'nama_paket') {
                        firstCard.querySelector('h4').textContent = this.value || 'Nama Paket';
                    }
                    else if (this.id === 'kecepatan') {
                        firstCard.querySelector('.preview-speed').textContent = this.value || 'Kecepatan';
                    }
                    else if (this.id === 'harga') {
                        firstCard.querySelector('.preview-price').textContent = this.value || 'Harga';
                    }
                    else if (this.name === 'fitur1') {
                        firstCard.querySelector('.preview-features li:nth-child(1)').textContent = this.value || 'Fitur 1';
                    }
                    else if (this.name === 'fitur2') {
                        firstCard.querySelector('.preview-features li:nth-child(2)').textContent = this.value || 'Fitur 2';
                    }
                    else if (this.name === 'fitur3') {
                        firstCard.querySelector('.preview-features li:nth-child(3)').textContent = this.value || 'Fitur 3';
                    }
                });
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const requiredFields = document.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#d32f2f';
                } else {
                    field.style.borderColor = '#ddd';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Harap isi semua field yang wajib diisi!');
            }
        });
        
        // Sidebar active state
        const currentPage = window.location.pathname.split('/').pop();
        const menuLinks = document.querySelectorAll('.admin-menu a');
        
        menuLinks.forEach(link => {
            const linkHref = link.getAttribute('href');
            if (currentPage === linkHref) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    </script>
</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($koneksi);
?>