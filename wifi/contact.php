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

// Ambil data informasi kontak dari database
$query_info = "SELECT * FROM informasi_kontak WHERE id = 1";
$result_info = mysqli_query($koneksi, $query_info);
$kontak_info = mysqli_fetch_assoc($result_info);

// Jika tidak ada data, gunakan data default
if (!$kontak_info) {
    $kontak_info = [
        'alamat' => 'Jl. Candi Unggul No.1, Jogotaan, Macanan, Kec. Kebakkramat, Kab. Karanganyar ',
        'telepon' => 'Customer Service: (021) 1234-5678<br>Teknis Support: (021) 1234-5679<br>Sales: (021) 1234-5680',
        'email' => 'info@wifinet.co.id<br>support@wifinet.co.id<br>sales@wifinet.co.id',
        'jam_operasional' => 'Senin - Minggu: 9:00 - 17:00<br>Sabtu: 9:00 - 15:00<br>Support Teknis: 10'
    ];
}

// Proses form kirim pesan
$pesan_terkirim = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kirim_pesan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['phone']);
    $subjek = mysqli_real_escape_string($koneksi, $_POST['subject']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['message']);
    
    // Validasi email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Simpan ke database
        $query_insert = "INSERT INTO kontak (nama, email, telepon, subjek, pesan, status) 
                         VALUES ('$nama', '$email', '$telepon', '$subjek', '$pesan', 'pending')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $pesan_terkirim = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - WiFiNet Solutions</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .page-header {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(44, 62, 80, 0.8)), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 2.8rem;
            margin-bottom: 15px;
        }
        
        .page-header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            background-color: white;
        }
        
        /* Modal untuk pesan sukses */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .modal-icon {
            font-size: 50px;
            color: #28a745;
            margin-bottom: 20px;
        }
        
        .modal-close {
            background-color: #2c5aa0;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
    </style>
</head>
<body>
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
                    <li><a href="services.php">Layanan</a></li>
                    <li><a href="coverage.html">Coverage Area</a></li>
                    <li><a href="contact.php" class="active">Kontak Kami</a></li>
                    <li><a href="register.html" class="register-btn">Daftar</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="page-header">
        <div class="container">
            <h1>Hubungi Kami</h1>
            <p>Kami siap membantu kebutuhan internet Anda</p>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="contact-container">
                <div class="contact-info">
                    <h2>Informasi Kontak</h2>
                    <p>Jangan ragu untuk menghubungi kami untuk informasi lebih lanjut tentang layanan atau bantuan teknis.</p>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Alamat Kantor Pusat</h4>
                            <p><?php echo str_replace('<br>', '<br>', $kontak_info['alamat']); ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Telepon</h4>
                            <p><?php echo str_replace('<br>', '<br>', $kontak_info['telepon']); ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p><?php echo str_replace('<br>', '<br>', $kontak_info['email']); ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Jam Operasional</h4>
                            <p><?php echo str_replace('<br>', '<br>', $kontak_info['jam_operasional']); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h2>Kirim Pesan</h2>
                    <form id="contactForm" method="POST" action="">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" required placeholder="Masukkan nama Anda">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required placeholder="Masukkan email Anda">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" required placeholder="Masukkan nomor telepon">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subjek</label>
                            <select id="subject" name="subject" required>
                                <option value="">Pilih subjek</option>
                                <option value="info">Informasi Layanan</option>
                                <option value="support">Support Teknis</option>
                                <option value="complaint">Keluhan</option>
                                <option value="business">Kerjasama Bisnis</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Pesan</label>
                            <textarea id="message" name="message" rows="5" required placeholder="Tulis pesan Anda di sini..."></textarea>
                        </div>
                        
                        <button type="submit" name="kirim_pesan" class="btn btn-primary">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <h2 class="section-title">Peta Lokasi</h2>
            <div class="map-container">
                <!-- Google Maps Embed -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613506864!3d-6.194741395493371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b759%3A0x6b45e67356080477!2sJakarta%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sid!2sid!4v1596023335113!5m2!1sid!2sid" width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        </div>
    </section>

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
                        <li><a href="services.html">Layanan</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Layanan</h3>
                    <ul>
                        <li><a href="services.html#home">Paket Rumahan</a></li>
                        <li><a href="services.html#business">Paket Bisnis</a></li>
                        <li><a href="services.html#public">Hotspot Publik</a></li>
                        <li><a href="services.html#support">Dukungan Teknis</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Kontak</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> <?php echo strip_tags(explode('<br>', $kontak_info['alamat'])[0]); ?></li>
                        <li><i class="fas fa-phone"></i> <?php echo strip_tags(explode('<br>', $kontak_info['telepon'])[0]); ?></li>
                        <li><i class="fas fa-envelope"></i> <?php echo strip_tags(explode('<br>', $kontak_info['email'])[0]); ?></li>
                        <li><i class="fas fa-clock"></i> <?php echo strip_tags(explode('<br>', $kontak_info['jam_operasional'])[0]); ?></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2023 WiFiNet Solutions. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Modal Pesan Sukses -->
    <div id="successModal" class="modal" style="<?php echo $pesan_terkirim ? 'display: block;' : 'display: none;'; ?>">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Pesan Terkirim!</h2>
            <p>Terima kasih telah menghubungi kami. Pesan Anda telah berhasil dikirim dan akan segera kami balas.</p>
            <button class="modal-close" onclick="document.getElementById('successModal').style.display='none'">Tutup</button>
        </div>
    </div>

    <script src="js/main.js"></script>
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
        
        // Tutup modal saat klik di luar modal
        window.onclick = function(event) {
            const modal = document.getElementById('successModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        </script>  
</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($koneksi);
?>