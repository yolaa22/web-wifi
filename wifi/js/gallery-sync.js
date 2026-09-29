// js/gallery-sync.js
class GallerySync {
    constructor() {
        this.lastUpdate = null;
        this.init();
    }

    init() {
        // Cek update setiap 3 detik
        setInterval(() => this.checkForUpdates(), 3000);
        
        // Deteksi perubahan di tab lain
        window.addEventListener('storage', (e) => {
            if (e.key === 'cantika_gallery_data') {
                this.onGalleryUpdated();
            }
        });
    }

    checkForUpdates() {
        const currentData = getGalleryData();
        const currentHash = JSON.stringify(currentData);
        
        if (this.lastUpdate && this.lastUpdate !== currentHash) {
            this.onGalleryUpdated();
        }
        
        this.lastUpdate = currentHash;
    }

    onGalleryUpdated() {
        console.log('Gallery updated, refreshing...');
        
        // Trigger custom event
        const event = new CustomEvent('galleryUpdated');
        window.dispatchEvent(event);
        
        // Refresh jika di halaman galeri
        if (window.location.pathname.includes('galeri.html')) {
            if (typeof renderGallery === 'function') {
                renderGallery();
            }
        }
        
        // Show notification
        this.showNotification('Galeri telah diperbarui');
    }

    showNotification(message) {
        // Buat notifikasi sederhana
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2c5aa0;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease;
        `;
        
        notification.innerHTML = `
            <i class="fas fa-sync-alt"></i> ${message}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}

// Tambahkan CSS untuk animasi
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Inisialisasi
let gallerySync = null;
document.addEventListener('DOMContentLoaded', () => {
    gallerySync = new GallerySync();
});