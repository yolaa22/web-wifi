<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan & Pembayaran - WiFiNet Solutions</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Custom styles untuk halaman tagihan */
        .invoice-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .invoice-summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .summary-card.pending {
            border-top: 4px solid #e74c3c;
        }
        
        .summary-card.paid {
            border-top: 4px solid #2ecc71;
        }
        
        .summary-card.overdue {
            border-top: 4px solid #f39c12;
        }
        
        .summary-card.total {
            border-top: 4px solid #3498db;
        }
        
        .summary-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }
        
        .summary-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .invoice-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-overdue {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-cancelled {
            background-color: #d6d8d9;
            color: #1b1e21;
        }
        
        .payment-method {
            display: inline-block;
            padding: 3px 10px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 0.8rem;
            color: #495057;
        }
        
        .payment-filter {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-group {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        
        .filter-item {
            flex: 1;
            min-width: 150px;
        }
        
        .filter-item label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        
        .filter-item select,
        .filter-item input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .invoice-detail-modal {
            max-width: 700px;
        }
        
        .invoice-detail-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .invoice-detail-body {
            padding: 20px;
        }
        
        .invoice-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .invoice-items {
            margin-bottom: 30px;
        }
        
        .invoice-total {
            text-align: right;
            border-top: 2px solid #eee;
            padding-top: 20px;
            margin-top: 20px;
        }
        
        .payment-history {
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .invoice-info-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-group {
                flex-direction: column;
            }
            
            .filter-item {
                width: 100%;
            }
        }
    </style>
</head>
<body class="admin-dashboard">
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-logo">
            <i class="fas fa-wifi logo-icon"></i>
            <h2>WiFi<span>Net</span> Admin</h2>
        </div>
        
        <div class="admin-menu">
            <ul>
                <li><a href="admin-dashboard.html"  ><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="pelanggan.php"><i class="fas fa-users"></i> <span>Layanan</span></a></li>
                <li><a href="laporan-gangguan.html" ><i class="fas fa-exclamation-triangle"></i> <span>Galeri Perusahaan</span></a></li>
                <li><a href="tagihan.html" class="active"><i class="fas fa-file-invoice-dollar"></i> <span>Galeri Tim</span></a></li>
                <li><a href="kontak.php"><i class="fas fa-network-wired"></i> <span>Kontak</span></a></li>
            </ul>
        </div>
        
        <div class="admin-logout">
            <a href="index.html"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="admin-main">
        <!-- Header -->
        <div class="admin-header">
            <h1>Tagihan & Pembayaran</h1>
            <div class="admin-user">
                <div class="admin-user-info">
                    <div class="admin-user-name">Admin WiFiNet</div>
                    <div class="admin-user-role">Administrator</div>
                </div>
                <div class="admin-avatar">A</div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="admin-content">
            <!-- Ringkasan Tagihan -->
            <div class="invoice-summary-cards">
                <div class="summary-card total">
                    <h3>Rp 48.500.000</h3>
                    <p>Total Pendapatan Bulan Ini</p>
                    <div style="color: #3498db; font-size: 0.9rem;">
                        <i class="fas fa-chart-line"></i> +12% dari bulan lalu
                    </div>
                </div>
                
                <div class="summary-card pending">
                    <h3>128</h3>
                    <p>Tagihan Tertunda</p>
                    <div style="color: #e74c3c; font-size: 0.9rem;">
                        <i class="fas fa-clock"></i> Rp 15.300.000
                    </div>
                </div>
                
                <div class="summary-card paid">
                    <h3>342</h3>
                    <p>Tagihan Dibayar</p>
                    <div style="color: #2ecc71; font-size: 0.9rem;">
                        <i class="fas fa-check-circle"></i> Rp 33.200.000
                    </div>
                </div>
                
                <div class="summary-card overdue">
                    <h3>24</h3>
                    <p>Tagihan Jatuh Tempo</p>
                    <div style="color: #f39c12; font-size: 0.9rem;">
                        <i class="fas fa-exclamation-triangle"></i> Rp 4.800.000
                    </div>
                </div>
            </div>
            
            <!-- Filter dan Aksi -->
            <div class="payment-filter">
                <div class="filter-group">
                    <div class="filter-item">
                        <label>Status Tagihan</label>
                        <select id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Tertunda</option>
                            <option value="paid">Dibayar</option>
                            <option value="overdue">Jatuh Tempo</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    
                    <div class="filter-item">
                        <label>Periode</label>
                        <select id="periodFilter">
                            <option value="">Semua Periode</option>
                            <option value="this_month">Bulan Ini</option>
                            <option value="last_month">Bulan Lalu</option>
                            <option value="this_year">Tahun Ini</option>
                            <option value="last_year">Tahun Lalu</option>
                        </select>
                    </div>
                    
                    <div class="filter-item">
                        <label>Tanggal Mulai</label>
                        <input type="text" id="startDate" placeholder="Pilih tanggal" class="datepicker">
                    </div>
                    
                    <div class="filter-item">
                        <label>Tanggal Akhir</label>
                        <input type="text" id="endDate" placeholder="Pilih tanggal" class="datepicker">
                    </div>
                    
                    <div class="filter-item">
                        <button id="applyFilter" class="btn btn-primary" style="height: 42px;">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button id="resetFilter" class="btn btn-secondary" style="height: 42px;">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Aksi Tagihan -->
            <div class="invoice-actions">
                <button id="generateInvoice" class="btn btn-primary">
                    <i class="fas fa-file-invoice"></i> Buat Tagihan Baru
                </button>
                <button id="sendReminder" class="btn btn-warning">
                    <i class="fas fa-bell"></i> Kirim Pengingat
                </button>
                <button id="exportInvoices" class="btn btn-success">
                    <i class="fas fa-file-export"></i> Export Data
                </button>
                <button id="printInvoices" class="btn btn-secondary">
                    <i class="fas fa-print"></i> Cetak Tagihan
                </button>
                <button id="bulkPayment" class="btn btn-info">
                    <i class="fas fa-money-bill-wave"></i> Proses Pembayaran
                </button>
            </div>
            
            <!-- Tabel Tagihan -->
            <div class="admin-table-container">
                <h2>Daftar Tagihan</h2>
                <div class="table-responsive">
                    <table class="admin-table" id="invoiceTable">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>No. Tagihan</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Periode</th>
                                <th>Jatuh Tempo</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Metode Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox" class="invoice-check"></td>
                                <td>INV-202305-001</td>
                                <td>Budi Santoso</td>
                                <td>Family 30Mbps</td>
                                <td>Mei 2023</td>
                                <td>25 Mei 2023</td>
                                <td>Rp 250.000</td>
                                <td><span class="invoice-status status-pending">Tertunda</span></td>
                                <td><span class="payment-method">Transfer Bank</span></td>
                                <td>
                                    <button class="btn-action btn-view" data-invoice="INV-202305-001"><i class="fas fa-eye"></i></button>
                                    <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-action btn-send"><i class="fas fa-paper-plane"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="invoice-check"></td>
                                <td>INV-202305-002</td>
                                <td>Sari Dewi</td>
                                <td>Premium 50Mbps</td>
                                <td>Mei 2023</td>
                                <td>25 Mei 2023</td>
                                <td>Rp 350.000</td>
                                <td><span class="invoice-status status-paid">Dibayar</span></td>
                                <td><span class="payment-method">Credit Card</span></td>
                                <td>
                                    <button class="btn-action btn-view" data-invoice="INV-202305-002"><i class="fas fa-eye"></i></button>
                                    <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-action btn-print"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="invoice-check"></td>
                                <td>INV-202305-003</td>
                                <td>Agus Wijaya</td>
                                <td>Startup 50Mbps</td>
                                <td>Mei 2023</td>
                                <td>20 Mei 2023</td>
                                <td>Rp 500.000</td>
                                <td><span class="invoice-status status-overdue">Jatuh Tempo</span></td>
                                <td><span class="payment-method">Transfer Bank</span></td>
                                <td>
                                    <button class="btn-action btn-view" data-invoice="INV-202305-003"><i class="fas fa-eye"></i></button>
                                    <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-action btn-remind"><i class="fas fa-bell"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="invoice-check"></td>
                                <td>INV-202305-004</td>
                                <td>Rina Fitriani</td>
                                <td>Corporate 100Mbps</td>
                                <td>Mei 2023</td>
                                <td>30 Mei 2023</td>
                                <td>Rp 1.000.000</td>
                                <td><span class="invoice-status status-pending">Tertunda</span></td>
                                <td><span class="payment-method">Direct Debit</span></td>
                                <td>
                                    <button class="btn-action btn-view" data-invoice="INV-202305-004"><i class="fas fa-eye"></i></button>
                                    <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-action btn-send"><i class="fas fa-paper-plane"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="invoice-check"></td>
                                <td>INV-202304-015</td>
                                <td>Hendra Pratama</td>
                                <td>Basic 10Mbps</td>
                                <td>Apr 2023</td>
                                <td>25 Apr 2023</td>
                                <td>Rp 150.000</td>
                                <td><span class="invoice-status status-paid">Dibayar</span></td>
                                <td><span class="payment-method">E-Wallet</span></td>
                                <td>
                                    <button class="btn-action btn-view" data-invoice="INV-202304-015"><i class="fas fa-eye"></i></button>
                                    <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-action btn-print"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="invoice-check"></td>
                                <td>INV-202304-012</td>
                                <td>Dewi Anggraini</td>
                                <td>Family 30Mbps</td>
                                <td>Apr 2023</td>
                                <td>25 Apr 2023</td>
                                <td>Rp 250.000</td>
                                <td><span class="invoice-status status-cancelled">Dibatalkan</span></td>
                                <td><span class="payment-method">-</span></td>
                                <td>
                                    <button class="btn-action btn-view" data-invoice="INV-202304-012"><i class="fas fa-eye"></i></button>
                                    <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-action btn-restore"><i class="fas fa-redo"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Grafik Statistik Pembayaran -->
            <div class="admin-table-container">
                <h2>Statistik Pembayaran</h2>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                    <div>
                        <canvas id="paymentChart" height="200"></canvas>
                    </div>
                    <div>
                        <h3>Rincian Status</h3>
                        <ul style="list-style: none; padding-left: 0;">
                            <li style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Tertunda:</span>
                                    <strong>128 Tagihan</strong>
                                </div>
                                <div style="background: #eee; height: 8px; border-radius: 4px; margin-top: 5px;">
                                    <div style="background: #e74c3c; width: 30%; height: 100%; border-radius: 4px;"></div>
                                </div>
                            </li>
                            <li style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Dibayar:</span>
                                    <strong>342 Tagihan</strong>
                                </div>
                                <div style="background: #eee; height: 8px; border-radius: 4px; margin-top: 5px;">
                                    <div style="background: #2ecc71; width: 80%; height: 100%; border-radius: 4px;"></div>
                                </div>
                            </li>
                            <li style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Jatuh Tempo:</span>
                                    <strong>24 Tagihan</strong>
                                </div>
                                <div style="background: #eee; height: 8px; border-radius: 4px; margin-top: 5px;">
                                    <div style="background: #f39c12; width: 5%; height: 100%; border-radius: 4px;"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Detail Tagihan -->
    <div id="invoiceDetailModal" class="modal">
        <div class="modal-content invoice-detail-modal">
            <div class="modal-header invoice-detail-header">
                <h3>Detail Tagihan: <span id="invoiceNumber">INV-202305-001</span></h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body invoice-detail-body">
                <div class="invoice-info-grid">
                    <div>
                        <h4>Informasi Pelanggan</h4>
                        <p><strong>Nama:</strong> Budi Santoso</p>
                        <p><strong>ID Pelanggan:</strong> PLG001</p>
                        <p><strong>Alamat:</strong> Jl. Teknologi No. 123, Jakarta Selatan</p>
                        <p><strong>Email:</strong> budi.santoso@email.com</p>
                        <p><strong>Telepon:</strong> 0812-3456-7890</p>
                    </div>
                    <div>
                        <h4>Informasi Tagihan</h4>
                        <p><strong>Tanggal Tagihan:</strong> 1 Mei 2023</p>
                        <p><strong>Jatuh Tempo:</strong> 25 Mei 2023</p>
                        <p><strong>Status:</strong> <span class="invoice-status status-pending">Tertunda</span></p>
                        <p><strong>Metode Pembayaran:</strong> Transfer Bank</p>
                        <p><strong>No. Referensi:</strong> TRF-78901234</p>
                    </div>
                </div>
                
                <div class="invoice-items">
                    <h4>Rincian Tagihan</h4>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 10px; text-align: left;">Deskripsi</th>
                                <th style="padding: 10px; text-align: center;">Jumlah</th>
                                <th style="padding: 10px; text-align: right;">Harga</th>
                                <th style="padding: 10px; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">Paket Family 30Mbps (Mei 2023)</td>
                                <td style="padding: 10px; text-align: center; border-bottom: 1px solid #eee;">1</td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">Rp 250.000</td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">Rp 250.000</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">Biaya Admin</td>
                                <td style="padding: 10px; text-align: center; border-bottom: 1px solid #eee;">1</td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">Rp 5.000</td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">Rp 5.000</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">PPN 11%</td>
                                <td style="padding: 10px; text-align: center; border-bottom: 1px solid #eee;">1</td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">Rp 27.500</td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">Rp 27.500</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="invoice-total">
                    <h3>Total: Rp 282.500</h3>
                    <p><small>Termasuk PPN 11%</small></p>
                </div>
                
                <div class="payment-history">
                    <h4>Riwayat Pembayaran</h4>
                    <p style="color: #666; font-style: italic;">Belum ada pembayaran</p>
                </div>
            </div>
            <div class="modal-footer" style="padding: 20px; text-align: right; border-top: 1px solid #eee;">
                <button class="btn btn-secondary close-modal">Tutup</button>
                <button class="btn btn-primary"><i class="fas fa-edit"></i> Edit Tagihan</button>
                <button class="btn btn-success"><i class="fas fa-paper-plane"></i> Kirim Ulang</button>
                <button class="btn btn-warning"><i class="fas fa-check-circle"></i> Tandai Dibayar</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="js/main.js"></script>
    <script src="js/admin.js"></script>
    <script>
        // Inisialisasi DataTable
        $(document).ready(function() {
            $('#invoiceTable').DataTable({
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ tagihan",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
            
            // Inisialisasi datepicker
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "id"
            });
            
            // Select all checkbox
            $('#selectAll').on('click', function() {
                $('.invoice-check').prop('checked', this.checked);
            });
            
            // Modal detail tagihan
            $('.btn-view').on('click', function() {
                const invoiceNum = $(this).data('invoice');
                $('#invoiceNumber').text(invoiceNum);
                $('#invoiceDetailModal').fadeIn();
            });
            
            // Close modal
            $('.close-modal').on('click', function() {
                $('#invoiceDetailModal').fadeOut();
            });
            
            // Filter aplikasi
            $('#applyFilter').on('click', function() {
                const status = $('#statusFilter').val();
                const period = $('#periodFilter').val();
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();
                
                // Simulasi filter (dalam implementasi nyata, ini akan request ke server)
                alert('Filter diterapkan:\nStatus: ' + (status || 'Semua') + 
                      '\nPeriode: ' + (period || 'Semua') +
                      '\nTanggal: ' + (startDate || '-') + ' sampai ' + (endDate || '-'));
            });
            
            // Reset filter
            $('#resetFilter').on('click', function() {
                $('#statusFilter').val('');
                $('#periodFilter').val('');
                $('#startDate').val('');
                $('#endDate').val('');
                $('#invoiceTable').DataTable().search('').draw();
            });
            
            // Inisialisasi chart
            const ctx = document.getElementById('paymentChart').getContext('2d');
            const paymentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Tagihan Dibayar (Juta)',
                        data: [38, 42, 45, 48, 52, 55],
                        borderColor: '#2ecc71',
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Tagihan Tertunda (Juta)',
                        data: [12, 10, 8, 15, 14, 10],
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Trend Pembayaran 6 Bulan Terakhir'
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>