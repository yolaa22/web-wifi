// js/admin.js - HANYA untuk halaman admin
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin JS loaded');
    
    // Cek apakah ini halaman admin
    if (!document.body.classList.contains('admin-dashboard')) {
        console.log('Not an admin page, skipping admin JS');
        return;
    }
    
    // Data dashboard
    const dashboardData = {
        totalCustomers: 5248,
        totalIssues: 42,
        pendingPayments: 128,
        totalPackages: 12
    };
    
    // Update dashboard stats
    updateDashboardStats(dashboardData);
    
    // Inisialisasi semua komponen
    initAll();
});

function updateDashboardStats(data) {
    document.querySelectorAll('.dashboard-card .card-content h3').forEach((card, index) => {
        const values = Object.values(data);
        if (index < values.length) {
            card.textContent = values[index].toLocaleString('id-ID');
        }
    });
}

function initAll() {
    // Inisialisasi DataTables
    initDataTables();
    
    // Inisialisasi modals
    initModals();
    
    // Inisialisasi charts
    initCharts();
    
    // Inisialisasi filter
    initFilters();
    
    // Inisialisasi event listeners
    initEventListeners();
}

function initDataTables() {
    // Tunggu jQuery dan DataTables tersedia
    if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
        console.log('jQuery atau DataTables tidak tersedia');
        return;
    }
    
    // Inisialisasi tabel paket
    if ($('#packageTable').length) {
        // Destroy jika sudah ada
        if ($.fn.DataTable.isDataTable('#packageTable')) {
            $('#packageTable').DataTable().destroy();
        }
        
        // Inisialisasi baru
        try {
            $('#packageTable').DataTable({
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ paket",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ paket",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                initComplete: function() {
                    console.log('DataTable initialized for #packageTable');
                }
            });
        } catch (error) {
            console.error('Error initializing DataTable for #packageTable:', error);
        }
    }
    
    // Inisialisasi tabel customer (jika ada di halaman lain)
    if ($('#customerTable').length) {
        if (!$.fn.DataTable.isDataTable('#customerTable')) {
            $('#customerTable').DataTable({
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ pelanggan",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ pelanggan",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        }
    }
    
    // Inisialisasi tabel invoice (jika ada di halaman lain)
    if ($('#invoiceTable').length) {
        if (!$.fn.DataTable.isDataTable('#invoiceTable')) {
            $('#invoiceTable').DataTable({
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ tagihan",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ tagihan",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        }
    }
}

function initModals() {
    // Select all checkbox
    $('#selectAll').on('click', function() {
        const isChecked = this.checked;
        $('.package-check, .invoice-check').prop('checked', isChecked);
    });
    
    // Modal detail paket
    $(document).on('click', '.btn-view[data-package]', function() {
        const packageId = $(this).data('package');
        const packageData = {
            'PKG-RES-001': 'Paket Basic',
            'PKG-RES-002': 'Paket Family',
            'PKG-RES-003': 'Paket Premium',
            'PKG-BIZ-001': 'Paket Startup',
            'PKG-BIZ-002': 'Paket Corporate',
            'PKG-BIZ-003': 'Paket Enterprise',
            'PKG-PUB-001': 'Hotspot Kafe'
        };
        
        $('#packageName').text(packageData[packageId] || 'Paket');
        $('#packageDetailModal').fadeIn();
    });
    
    // Close modal
    $(document).on('click', '.close-modal', function() {
        $(this).closest('.modal').fadeOut();
    });
    
    // Close modal dengan ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('.modal').fadeOut();
        }
    });
}

function initCharts() {
    if (typeof Chart === 'undefined') {
        console.log('Chart.js tidak tersedia');
        return;
    }
    
    // Package Chart
    const packageCtx = document.getElementById('packageChart');
    if (packageCtx) {
        try {
            new Chart(packageCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Basic', 'Family', 'Premium', 'Startup', 'Corporate', 'Enterprise'],
                    datasets: [{
                        label: 'Jumlah Pelanggan',
                        data: [312, 408, 104, 86, 52, 18],
                        backgroundColor: [
                            '#3498db', '#2ecc71', '#e74c3c',
                            '#9b59b6', '#f39c12', '#34495e'
                        ],
                        borderColor: [
                            '#2980b9', '#27ae60', '#c0392b',
                            '#8e44ad', '#d35400', '#2c3e50'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: 'Distribusi Pelanggan per Paket' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Jumlah Pelanggan' }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating package chart:', error);
        }
    }
    
    // Revenue Chart (untuk dashboard)
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        try {
            new Chart(revenueCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Pendapatan (Juta Rp)',
                        data: [45, 52, 48, 55, 58, 62],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value + ' JT';
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating revenue chart:', error);
        }
    }
}

function initFilters() {
    // Filter paket
    $('#applyFilter').on('click', function() {
        const category = $('#categoryFilter').val();
        const status = $('#statusFilter').val();
        const search = $('#searchPackage').val();
        
        console.log('Filter diterapkan:', { category, status, search });
        
        // Filter DataTable
        if ($('#packageTable').length) {
            const table = $('#packageTable').DataTable();
            
            // Reset search
            table.search('');
            
            // Apply custom filtering (ini contoh sederhana)
            // Di implementasi nyata, ini akan request ke server
            table.draw();
        }
    });
    
    // Reset filter
    $('#resetFilter').on('click', function() {
        $('#categoryFilter').val('');
        $('#statusFilter').val('');
        $('#searchPackage').val('');
        
        // Reset DataTable search
        if ($('#packageTable').length) {
            const table = $('#packageTable').DataTable();
            table.search('').draw();
        }
    });
    
    // Flatpickr initialization
    if (typeof flatpickr !== 'undefined') {
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d",
            locale: "id"
        });
    }
}

function initEventListeners() {
    // Tambah paket baru
    $('#addPackage').on('click', function() {
        alert('Fitur tambah paket baru akan segera tersedia!');
    });
    
    // Import packages
    $('#importPackages').on('click', function() {
        alert('Fitur import paket akan segera tersedia!');
    });
    
    // Export packages
    $('#exportPackages').on('click', function() {
        alert('Mengekspor data paket...');
    });
    
    // Bulk action
    $('#bulkAction').on('click', function() {
        const selected = $('.package-check:checked').length;
        if (selected > 0) {
            alert(`Aksi massal untuk ${selected} paket terpilih`);
        } else {
            alert('Pilih setidaknya satu paket terlebih dahulu');
        }
    });
    
    // Refresh packages
    $('#refreshPackages').on('click', function() {
        location.reload();
    });
    
    // Edit package buttons
    $(document).on('click', '.btn-edit', function() {
        alert('Fitur edit paket akan segera tersedia!');
    });
    
    // Copy package buttons
    $(document).on('click', '.btn-copy', function() {
        alert('Fitur duplikat paket akan segera tersedia!');
    });
}