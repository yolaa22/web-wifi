// js/main.js - HANYA untuk halaman publik (non-admin)
document.addEventListener('DOMContentLoaded', function() {
    console.log('Main JS loaded - Public Pages Only');
    
    // Cek apakah ini halaman admin, jika iya, JANGAN jalankan kode ini
    if (document.body.classList.contains('admin-dashboard')) {
        console.log('Admin page detected, skipping public JS');
        return;
    }
    
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navMenu = document.getElementById('navMenu');
    
    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            const icon = mobileMenuBtn.querySelector('i');
            
            if (navMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
    
    // Tutup menu mobile ketika link diklik
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (navMenu && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                if (mobileMenuBtn) {
                    const icon = mobileMenuBtn.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    });
    
    // Counter animation
    const statNumbers = document.querySelectorAll('.stat-number');
    if (statNumbers.length > 0) {
        const animateCounter = () => {
            statNumbers.forEach(stat => {
                const target = parseInt(stat.getAttribute('data-count')) || 0;
                const speed = 200;
                const increment = target / speed;
                let current = 0;
                
                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        stat.textContent = Math.ceil(current);
                        setTimeout(updateCounter, 1);
                    } else {
                        stat.textContent = target;
                    }
                };
                
                updateCounter();
            });
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        const statsSection = document.querySelector('.stats');
        if (statsSection) {
            observer.observe(statsSection);
        }
    }
    
    // Form kontak
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const name = formData.get('name');
            const email = formData.get('email');
            const subject = formData.get('subject');
            const message = formData.get('message');
            
            if (!name || !email || !subject || !message) {
                alert('Harap lengkapi semua field!');
                return;
            }
            
            alert(`Terima kasih ${name}! Pesan Anda telah dikirim.`);
            this.reset();
        });
    }
    
    // Set active nav
    const currentPage = window.location.pathname.split('/').pop();
    const navLinksList = document.querySelectorAll('nav a');
    
    navLinksList.forEach(link => {
        const linkPage = link.getAttribute('href');
        if (linkPage === currentPage || (currentPage === '' && linkPage === 'index.html')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
});