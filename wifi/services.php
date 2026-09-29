<?php
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

// Ambil data paket layanan dari database
$query_paket = "SELECT * FROM paket_layanan ORDER BY kategori, CAST(SUBSTRING_INDEX(harga, ' ', 1) AS UNSIGNED)";
$result_paket = mysqli_query($koneksi, $query_paket);

// Ambil kategori unik untuk grouping
$query_kategori = "SELECT DISTINCT kategori FROM paket_layanan ORDER BY kategori";
$result_kategori = mysqli_query($koneksi, $query_kategori);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Layanan Internet - WiFiNet Solutions</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="css/services.css">
    <style>
        /* Dropdown Wrapper */
.dropdown {
    position: relative;
}

/* Dropdown Menu Hidden by Default */
.dropdown-menu {
    position: absolute;
    top: 50px;
    left: 0;
    background: white;
    width: 220px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    border-radius: 6px;
    padding: 10px 0;
    opacity: 0;
    visibility: hidden;
    transition: 0.25s ease;
    border: 1px solid #eaeaea;
    z-index: 99;
}

/* Show on Hover */
.dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    top: 45px;
}

/* Dropdown item */
.dropdown-menu li {
    list-style: none;
}

.dropdown-menu a {
    display: block;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    transition: 0.25s;
    font-size: 0.95rem;
}

/* Hover Effect like Jagonet */
.dropdown-menu a:hover {
    background-color: #2c5aa0;
    color: white;
}

/* Dropdown arrow */
.dropdown-icon {
    font-size: 0.7rem;
    margin-left: 6px;
    transition: 0.3s;
    color: #ffffff;
}

/* Rotate arrow on hover */
.dropdown:hover .dropdown-icon {
    transform: rotate(180deg);
    color: #2c5aa0;
}

/* Mobile optimization */
@media (max-width: 768px) {
    .dropdown-menu {
        position: relative;
        box-shadow: none;
        width: 100%;
        border: none;
        top: 0;
        opacity: 1;
        visibility: visible;
        padding-left: 15px;
    }

    .dropdown:hover .dropdown-menu {
        top: 0;
    }
}
/* ====== DROPDOWN MENURUN KE BAWAH ====== */

.dropdown {
    position: relative;
}

/* posisi dropdown */
.dropdown-menu {
    position: absolute;
    top: 100%;         /* langsung di bawah menu */
    left: 0;
    background: white;
    width: 200px;
    border: 1px solid #eaeaea;
    border-radius: 6px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    padding: 10px 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: 0.25s ease;
    z-index: 100;
}

/* ketika hover → tampilkan dropdown */
.dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

/* item dropdown */
.dropdown-menu li {
    list-style: none;
}

.dropdown-menu a {
    display: block;
    padding: 12px 18px;
    font-size: 0.95rem;
    color: #333;
    text-decoration: none;
    transition: 0.25s ease;
}

/* hover warna seperti navbar */
.dropdown-menu a:hover {
    background: #2c5aa0;
    color: white;
}

/* icon panah */
.dropdown-icon {
    margin-left: 6px;
    font-size: 0.7rem;
    transition: 0.3s ease;
}

.dropdown:hover .dropdown-icon {
    transform: rotate(180deg);
    color: #2c5aa0;
}

/* Mobile: dropdown selalu tampil vertikal */
@media (max-width: 768px) {
    .dropdown-menu {
        position: relative;
        width: 50%;
        box-shadow: none;
        border: none;
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        padding-left: 15px;
    }
}
/* === DROPDOWN MENU WRAPPER === */
.dropdown-menu {
    list-style: none;
    position: absolute;
    top: 100%;              /* tempel tepat di bawah menu */
    left: 0;
    background: #ffffff;    /* background putih */
    min-width: 220px;
    padding: 0;
    margin: 0;
    display: none;
    flex-direction: column;
    border-radius: 0 0 6px 6px;
    z-index: 999;
    border: 1px solid #ddd;
    box-shadow: 0 8px 12px rgba(0,0,0,0.15);
}

/* item dropdown */
.dropdown-menu li a {
    padding: 5px 8px;
    font-size: 15px;
    color: #333;
    display: block;
    border-bottom: 1px solid #eee;
}

/* hover item */
.dropdown-menu li a:hover {
    background: #f0f0f0;
}

/* === FIX UTAMA: dropdown tetap muncul saat mouse ada di tombol ATAU di dalam dropdown === */
.dropdown:hover .dropdown-menu,
.dropdown-menu:hover {
    display: flex;
}

/* === FIX GAP: hilangkan padding / jarak di elemen parent === */
.dropdown a.menu-link {
    padding-bottom: 5px !important;
    margin: 0;    
}

.dropdown {
    padding: 0;
    margin: 0;
}

/* icon panah */
.dropdown-icon {
    margin-left: 3px;
    transition: 0.3s ease;
}

.dropdown:hover .dropdown-icon {
    transform: rotate(180deg);
}
/* ===== FIX: dropdown tidak lagi hilang saat kursor turun ===== */
/* pastikan ini di-include setelah semua aturan dropdown lama agar override */

#navMenu > li.dropdown {
  position: relative;
  /* buat area klik/hover konsisten */
}

/* Tempel sedikit ke atas (overlap 2px) supaya tidak ada gap */
#navMenu > li.dropdown .dropdown-menu {
  position: absolute;
  top: calc(100% - 2px);   /* overlap 2px -> mencegah gap saat pindah mouse */
  left: 0;
  min-width: 220px;
  background: #ffffff;
  border: 1px solid #ddd;
  border-radius: 0 0 6px 6px;
  box-shadow: 0 8px 12px rgba(0,0,0,0.12);
  padding: 4px 0;          /* kecilkan padding pembungkus */
  margin: 0;
  display: none;           /* tampilkan via .open */
  z-index: 9999;
  transform: none;         /* pastikan tidak ada translate */
  pointer-events: auto;
}

/* item style */
#navMenu > li.dropdown .dropdown-menu li a {
  padding: 5px 8px;
  color: #333;
  border-bottom: 1px solid #eee;
  display: block;
}

/* hover pada item */
#navMenu > li.dropdown .dropdown-menu li a:hover {
  background: #f0f0f0;
}

/* class .open untuk menampilkan (JS akan toggle) */
#navMenu > li.dropdown.open .dropdown-menu {
  display: flex;
  flex-direction: column;
}

/* tetap responsif: di mobile .open juga dipakai */
@media (max-width: 768px) {
  #navMenu > li.dropdown .dropdown-menu {
    position: relative;
    top: 0;
    box-shadow: none;
    border: none;
    display: none;
    padding-left: 12px;
  }
  #navMenu > li.dropdown.open .dropdown-menu { display: block; }
}

/* safety: hindari parent memotong dropdown */
.header-container, header, nav, .container {
  overflow: visible !important;
}
    </style>
</head>
<body>
    <!-- Header -->
    <header>
    <div class="container header-container">
        <div class="logo-container">
            <div class="logo">
                <img src="images/logo.png" alt="Logo PT. CANTIKA DATA PRIMA">
            </div>
        </div>
        
        <div class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </div>
        
        <nav>
            <ul id="navMenu">
                <li><a href="index.html">Beranda</a></li>
                <li class="dropdown">
                    <a href="">
                        Tentang Kami
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                
                    <ul class="dropdown-menu">
                        <li><a href="sejarah.html">Sejarah Perusahaan</a></li>
                        <li><a href="galeri.html">Galeri Perusahaan</a></li>
                        <li><a href="tim.html">Team Works</a></li>
                    </ul>
                </li>                
                
                <script>
                function goToPage() {
                    var selected = document.getElementById("aboutOptions").value;
                    if (selected) {
                        window.location.href = selected;
                    }
                }
                </script>
                <li><a href="services.php" class="active">Layanan</a></li>
                <li><a href="coverage.html">Coverage Area</a></li>
                <li><a href="contact.php">Kontak Kami</a></li>
                <li><a href="register.html" class="register-btn">Daftar</a></li>
            </ul>
        </nav>
    </div>
</header>

    <!-- About Services -->
    <section class="service-intro">
        <div class="container">
            <div class="section-title">
                <h2>Layanan Koneksi Internet</h2>
                <p>Solusi koneksi internet terbaik untuk semua kebutuhan</p>
            </div>
            <div class="intro-content">
                <div class="intro-image">
                    <img src="images/jaringan.jpg" alt="Fitur Layanan">
                </div>
                <div class="intro-text">
                    <p>Internet merupakan salah satu kebutuhan utama bagi masyarakat global. Menyadari bahwa para pelaku bisnis di Indonesia membutuhkan layanan sambungan internet yang optimal agar mampu mengejar perkembangan industri dunia, kami mengembangkan bentuk layanan sambungan internet yang sepenuhnya dapat disesuaikan dengan kebutuhan pengguna.</p>
                    <p>Bentuk layanan sambungan internet yang kami kembangkan berbasis model infrastruktur jaringan modern, memberikan fleksibilitas maksimal bagi pelanggan.</p>
                    <a href="contact.html" class="btn-primary">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Dedicated Internet -->
    <section class="dedicated-service">
        <div class="container">
    
            <!-- Section Title -->
            <div class="section-title">
                <h2>Dedicated Internet Access</h2>
            </div>
    
            <div class="dedicated-content">
    
                <!-- Text Section -->
                <div class="dedicated-text">
                    <p>
                        <strong>Dedicated Internet Access</strong> adalah salah satu solusi yang menawarkan kecepatan
                        dan fleksibilitas sesuai dengan pertumbuhan bisnis, memungkinkan transfer data cepat untuk
                        hosting, server email, website, dan aplikasi lainnya.
                    </p>
                    <p>
                        Kami memberikan koneksi 24 jam ke Global Internet dengan alokasi bandwidth dedicated (1:1)
                        untuk memastikan performa jaringan selalu dalam keadaan terbaik setiap waktu.
                    </p>
                </div>
    
                <!-- Features Section -->
                <div class="dedicated-features">
    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <img src="assets/img/peed.png" alt="Speed">
                        </div>
                        <div class="feature-info">
                            <h4>Ultra-speed Connection</h4>
                            <p>Dengan rasio download dan upload terbaik, kami memberikan rasio sampai 1:1.</p>
                        </div>
                    </div>
    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <img src="assets/img/bandwidth.png" alt="Bandwidth">
                        </div>
                        <div class="feature-info">
                            <h4>SLA 99.50%</h4>
                            <p>Jaminan tertinggi untuk Uptime kelas dunia dengan tautan berlebihan.</p>
                        </div>
                    </div>
    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <img src="assets/img/monitor.png" alt="Monitoring">
                        </div>
                        <div class="feature-info">
                            <h4>MRTG Monitoring</h4>
                            <p>Pemantauan trafik real-time untuk distribusi bandwidth yang optimal.</p>
                        </div>
                    </div>
    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <img src="assets/img/help-desk.png" alt="Support">
                        </div>
                        <div class="feature-info">
                            <h4>Fast Support 24/7</h4>
                            <p>Tim khusus bekerja untuk memaksimalkan performa jaringan.</p>
                        </div>
                    </div>
    
                </div>
    
            </div>
        </div>
    </section>    

    <!-- Paket Layanan -->
    <section class="packages-section">
        <div class="container">
            <div class="section-title">
                <h2>Paket Layanan Internet</h2>
                <p>Pilih paket sesuai kebutuhan Anda</p>
            </div>
            
            <?php
            // Tampilkan paket berdasarkan kategori
            while ($kategori_row = mysqli_fetch_assoc($result_kategori)) {
                $kategori = $kategori_row['kategori'];
                
                // Ambil paket untuk kategori ini
                $query_paket_kategori = "SELECT * FROM paket_layanan WHERE kategori = '$kategori' ORDER BY CAST(SUBSTRING_INDEX(harga, ' ', 1) AS UNSIGNED)";
                $result_paket_kategori = mysqli_query($koneksi, $query_paket_kategori);
                
                if (mysqli_num_rows($result_paket_kategori) > 0):
            ?>
                <!-- Paket Kategori -->
                <div class="package-category">
                    <h3 class="category-title"><?php echo htmlspecialchars($kategori); ?></h3>
                    <div class="package-grid">
                        <?php while ($paket = mysqli_fetch_assoc($result_paket_kategori)): ?>
                            <!-- Paket Card -->
                            <div class="package-card">
                                <div class="package-header">
                                    <h4><?php echo htmlspecialchars($paket['nama_paket']); ?></h4>
                                    <div class="package-speed">
                                        <span><?php echo htmlspecialchars($paket['kecepatan']); ?></span>
                                    </div>
                                </div>
                                <div class="package-price">
                                    <span class="price"><?php echo htmlspecialchars($paket['harga']); ?></span>
                                    <span class="period">/bulan</span>
                                </div>
                                <ul class="package-features">
                                    <li><i class="ri-check-line"></i> <?php echo htmlspecialchars($paket['fitur1']); ?></li>
                                    <li><i class="ri-check-line"></i> <?php echo htmlspecialchars($paket['fitur2']); ?></li>
                                    <li><i class="ri-check-line"></i> <?php echo htmlspecialchars($paket['fitur3']); ?></li>
                                </ul>
                                <?php if ($paket['tombol_text'] == 'Hubungi Kami'): ?>
                                    <a href="contact.html" class="btn-order"><?php echo htmlspecialchars($paket['tombol_text']); ?></a>
                                <?php else: ?>
                                    <a href="register.html" class="btn-order"><?php echo htmlspecialchars($paket['tombol_text']); ?></a>
                                <?php endif; ?>
                                <p style="margin-top: 10px; font-size: 12px; color: #888; text-align: center;">
                                    <i class="fas fa-sync-alt"></i> Data terupdate
                                </p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php
                endif;
                mysqli_free_result($result_paket_kategori);
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="logo">
                        <img src="images/logo.png" alt="Logo WiFiNet">
                    </div>
                    <p>Penyedia layanan internet WiFi terdepan dengan teknologi terkini dan pelayanan terbaik.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h3>Menu</h3>
                    <ul>
                        <li><a href="index.html">Beranda</a></li>
                        <li><a href="about.html">Tentang Perusahaan</a></li>
                        <li><a href="services.php">Layanan</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Layanan</h3>
                    <ul>
                        <li><a href="#home">Paket Rumahan</a></li>
                        <li><a href="#business">Paket Bisnis</a></li>
                        <li><a href="#public">Hotspot Publik</a></li>
                        <li><a href="#support">Dukungan Teknis</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Kontak</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> Jl. Teknologi No. 123, Jakarta</li>
                        <li><i class="fas fa-phone"></i> (021) 1234-5678</li>
                        <li><i class="fas fa-envelope"></i> info@wifinet.co.id</li>
                        <li><i class="fas fa-clock"></i> Senin - Jumat: 08.00 - 17.00</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> WiFiNet Solutions. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function(){
            document.getElementById('navMenu').classList.toggle('open');
        });

        // Tab Switching
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all buttons and contents
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button and corresponding content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
    <script>
        /* Dropdown hover/toggle yang andal:
           - mouseenter -> buka segera
           - mouseleave -> delay kecil (150ms) sebelum tutup, mencegah flicker
           - click pada mobile -> toggle
        */
        document.addEventListener('DOMContentLoaded', function () {
          const DROPDOWN_CLOSE_DELAY = 150; // ms
          const dropdownItems = document.querySelectorAll('#navMenu > li.dropdown');
        
          dropdownItems.forEach(function (item) {
            let closeTimer = null;
            const trigger = item.querySelector('a.menu-link') || item.querySelector('a');
            const menu = item.querySelector('.dropdown-menu');
        
            if (!trigger || !menu) return;
        
            // buka saat mouse masuk area parent
            item.addEventListener('mouseenter', function () {
              clearTimeout(closeTimer);
              item.classList.add('open');
              trigger.setAttribute('aria-expanded', 'true');
            });
        
            // mulai timer tutup saat mouse keluar parent
            item.addEventListener('mouseleave', function () {
              clearTimeout(closeTimer);
              closeTimer = setTimeout(function () {
                item.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
              }, DROPDOWN_CLOSE_DELAY);
            });
        
            // juga jaga saat mouse berada di dalam menu (mencegah flicker)
            menu.addEventListener('mouseenter', function () {
              clearTimeout(closeTimer);
              item.classList.add('open');
              trigger.setAttribute('aria-expanded', 'true');
            });
            menu.addEventListener('mouseleave', function () {
              clearTimeout(closeTimer);
              closeTimer = setTimeout(function () {
                item.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
              }, DROPDOWN_CLOSE_DELAY);
            });
        
            // toggle di perangkat sentuh / mobile (klik)
            trigger.addEventListener('click', function (e) {
              if (window.innerWidth <= 768) {
                e.preventDefault();
                // toggle open class
                const isOpen = item.classList.contains('open');
                if (isOpen) {
                  item.classList.remove('open');
                  trigger.setAttribute('aria-expanded', 'false');
                } else {
                  // close other dropdowns
                  document.querySelectorAll('#navMenu > li.dropdown.open').forEach(function (d) {
                    d.classList.remove('open');
                    const t = d.querySelector('a.menu-link') || d.querySelector('a');
                    t && t.setAttribute('aria-expanded', 'false');
                  });
                  item.classList.add('open');
                  trigger.setAttribute('aria-expanded', 'true');
                }
              }
            });
        
            // aksesibilitas: ESC untuk tutup
            document.addEventListener('keyup', function (ev) {
              if (ev.key === 'Escape') {
                item.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
              }
            });
          });
        
          // Tutup semua jika klik di luar
          document.addEventListener('click', function (ev) {
            if (!ev.target.closest('#navMenu')) {
              document.querySelectorAll('#navMenu > li.dropdown.open').forEach(function (d) {
                d.classList.remove('open');
                const t = d.querySelector('a.menu-link') || d.querySelector('a');
                t && t.setAttribute('aria-expanded', 'false');
              });
            }
          });
        });
        </script>  
</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($koneksi);
?>