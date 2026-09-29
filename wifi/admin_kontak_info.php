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

// Tangani form update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $jam_operasional = mysqli_real_escape_string($koneksi, $_POST['jam_operasional']);
    
    // Update data - asumsi hanya ada 1 record dengan id=1
    $query = "UPDATE informasi_kontak SET 
              alamat = '$alamat',
              telepon = '$telepon',
              email = '$email',
              jam_operasional = '$jam_operasional',
              updated_at = NOW()
              WHERE id = 1";
    
    if (mysqli_query($koneksi, $query)) {
        $pesan = "Informasi kontak berhasil diperbarui!";
        $_SESSION['pesan'] = $pesan;
        header("Location: admin_kontak_info.php");
        exit();
    } else {
        $pesan_error = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}

// Ambil data informasi kontak dari database
$query_info = "SELECT * FROM informasi_kontak WHERE id = 1";
$result_info = mysqli_query($koneksi, $query_info);
$kontak_info = mysqli_fetch_assoc($result_info);

// Jika tidak ada data, buat data default
if (!$kontak_info) {
    // Buat data default
    $query_insert = "INSERT INTO informasi_kontak (id, alamat, telepon, email, jam_operasional) 
                     VALUES (1, 
                     'Jl. Teknologi No. 123, Gedung WiFiNet Tower<br>Jakarta Selatan 12940, Indonesia', 
                     'Customer Service: (021) 1234-5678<br>Teknis Support: (021) 1234-5679<br>Sales: (021) 1234-5680', 
                     'info@wifinet.co.id<br>support@wifinet.co.id<br>sales@wifinet.co.id', 
                     'Senin - Jumat: 8:00 - 17:00<br>Sabtu: 9:00 - 15:00<br>Support Teknis: 24/7')";
    mysqli_query($koneksi, $query_insert);
    $kontak_info = mysqli_fetch_assoc(mysqli_query($koneksi, $query_info));
}

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
    <title>Admin Kontak - WiFiNet</title>
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

        .stat-icon.address {
            background-color: #e3eeff;
            color: #2c5aa0;
        }

        .stat-icon.phone {
            background-color: #e8f5e9;
            color: #388e3c;
        }

        .stat-icon.email {
            background-color: #fff3e0;
            color: #f57c00;
        }

        .stat-icon.time {
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

        /* Form Container */
        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        /* Form */
        .form-group {
            margin-bottom: 25px;
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

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-note {
            font-size: 0.85rem;
            color: #777;
            margin-top: 5px;
            display: block;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-update {
            background-color: #28a745;
            color: white;
            flex: 1;
        }

        .btn-update:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .btn-preview {
            background-color: #17a2b8;
            color: white;
            text-decoration: none;
            justify-content: center;
        }

        .btn-preview:hover {
            background-color: #138496;
            transform: translateY(-2px);
        }

        /* Preview Section */
        .preview-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .preview-container h3 {
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid #2c5aa0;
            transition: all 0.3s;
            height: 100%;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .info-card h4 {
            color: #2c5aa0;
            margin-bottom: 15px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card p {
            color: #555;
            line-height: 1.6;
        }

        .last-updated {
            text-align: center;
            color: #777;
            font-size: 0.9rem;
            padding-top: 20px;
            border-top: 1px solid #eee;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
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
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-logo">
            <i class="fas fa-network-wired logo-icon"></i>
            <h2>WiFiNet<span>Admin</span></h2>
        </div>
        
        <div class="admin-menu">
            <ul>
                <li><a href="admin-dashboard.html"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="admin_paket.php"><i class="fas fa-wifi"></i> <span>Layanan</span></a></li>
                <li><a href="admin-galeri.html"><i class="fas fa-images"></i> <span>Galeri Perusahaan</span></a></li>
                <li><a href="admin-tim.html"><i class="fas fa-users"></i> <span>Galeri Tim</span></a></li>
                <li><a href="admin_kontak_info.php" class="active"><i class="fas fa-address-card"></i> <span>Kontak</span></a></li>
            </ul>
        </div>
        
        <div class="admin-logout">
            <a href="index.html"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content-wrapper">
        <div class="content-header">
            <h1><i class="fas fa-address-card"></i> Kelola Informasi Kontak</h1>
            <a href="contact.php" target="_blank" class="btn" style="background-color: #2c5aa0; color: white; text-decoration: none;">
                <i class="fas fa-eye"></i> Lihat Halaman Kontak
            </a>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon address">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stat-info">
                    <h3>1</h3>
                    <p>Alamat Kantor</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon phone">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="stat-info">
                    <h3>3</h3>
                    <p>Nomor Telepon</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon email">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-info">
                    <h3>3</h3>
                    <p>Email Kontak</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon time">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>24/7</h3>
                    <p>Support Teknis</p>
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

        <!-- Form -->
        <div class="form-container">
            <h2 style="color: #2c5aa0; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #2c5aa0;">
                <i class="fas fa-edit"></i> Edit Informasi Kontak
            </h2>
            
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat Kantor Pusat</label>
                    <textarea id="alamat" name="alamat" class="form-control" required><?php echo htmlspecialchars($kontak_info['alamat']); ?></textarea>
                    <span class="form-note">Gunakan &lt;br&gt; untuk baris baru</span>
                </div>
                
                <div class="form-group">
                    <label for="telepon"><i class="fas fa-phone"></i> Nomor Telepon</label>
                    <textarea id="telepon" name="telepon" class="form-control" required><?php echo htmlspecialchars($kontak_info['telepon']); ?></textarea>
                    <span class="form-note">Gunakan &lt;br&gt; untuk baris baru</span>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <textarea id="email" name="email" class="form-control" required><?php echo htmlspecialchars($kontak_info['email']); ?></textarea>
                    <span class="form-note">Gunakan &lt;br&gt; untuk baris baru</span>
                </div>
                
                <div class="form-group">
                    <label for="jam_operasional"><i class="fas fa-clock"></i> Jam Operasional</label>
                    <textarea id="jam_operasional" name="jam_operasional" class="form-control" required><?php echo htmlspecialchars($kontak_info['jam_operasional']); ?></textarea>
                    <span class="form-note">Gunakan &lt;br&gt; untuk baris baru</span>
                </div>
                
                <div class="btn-group">
                    <button type="submit" name="update" class="btn btn-update">
                        <i class="fas fa-save"></i> Update Informasi Kontak
                    </button>
                    <a href="kontak.php" class="btn btn-preview">
                        <i class="fas fa-database"></i> Database Pesan
                    </a>
                </div>
            </form>
        </div>

        <!-- Preview -->
        <div class="preview-container">
            <h3><i class="fas fa-eye"></i> Preview Informasi Kontak</h3>
            
            <div class="preview-grid">
                <div class="info-card">
                    <h4><i class="fas fa-map-marker-alt"></i> Alamat Kantor Pusat</h4>
                    <p><?php echo nl2br(htmlspecialchars(str_replace('<br>', "\n", $kontak_info['alamat']))); ?></p>
                </div>
                
                <div class="info-card">
                    <h4><i class="fas fa-phone"></i> Telepon</h4>
                    <p><?php echo nl2br(htmlspecialchars(str_replace('<br>', "\n", $kontak_info['telepon']))); ?></p>
                </div>
                
                <div class="info-card">
                    <h4><i class="fas fa-envelope"></i> Email</h4>
                    <p><?php echo nl2br(htmlspecialchars(str_replace('<br>', "\n", $kontak_info['email']))); ?></p>
                </div>
                
                <div class="info-card">
                    <h4><i class="fas fa-clock"></i> Jam Operasional</h4>
                    <p><?php echo nl2br(htmlspecialchars(str_replace('<br>', "\n", $kontak_info['jam_operasional']))); ?></p>
                </div>
            </div>
            
            <div class="last-updated">
                <i class="fas fa-info-circle"></i>
                <span>Terakhir diperbarui: <?php echo date('d-m-Y H:i', strtotime($kontak_info['updated_at'])); ?></span>
            </div>
        </div>
    </main>

    <script>
        // Auto-hide pesan sukses setelah 5 detik
        setTimeout(function() {
            const alertSuccess = document.querySelector('.alert-success');
            if (alertSuccess) {
                alertSuccess.style.transition = 'opacity 0.5s';
                alertSuccess.style.opacity = '0';
                setTimeout(() => alertSuccess.remove(), 500);
            }
        }, 5000);
        
        // Preview real-time saat mengetik
        const inputs = document.querySelectorAll('#alamat, #telepon, #email, #jam_operasional');
        const previews = {
            'alamat': document.querySelectorAll('.info-card')[0].querySelector('p'),
            'telepon': document.querySelectorAll('.info-card')[1].querySelector('p'),
            'email': document.querySelectorAll('.info-card')[2].querySelector('p'),
            'jam_operasional': document.querySelectorAll('.info-card')[3].querySelector('p')
        };
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const id = this.id;
                if (previews[id]) {
                    // Ganti <br> dengan newline untuk preview
                    const text = this.value.replace(/<br\s*\/?>/gi, '\n');
                    previews[id].textContent = text;
                }
            });
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
    </script>
</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($koneksi);
?>