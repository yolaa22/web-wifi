// js/gallery-data.js
const GALLERY_STORAGE_KEY = 'cantika_gallery_data';

// Data default jika belum ada di localStorage
const defaultGalleryItems = [
    {
        id: 1,
        title: "Instalasi Jaringan Fiber Optic",
        description: "Proses instalasi jaringan fiber optic di area perumahan",
        category: "instalasi",
        image: "https://images.unsplash.com/photo-1563013544-824ae1b704d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
    },
    {
        id: 2,
        title: "Tim Teknisi di Lapangan",
        description: "Tim teknisi kami sedang melakukan maintenance jaringan",
        category: "tim",
        image: "https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
    },
    {
        id: 3,
        title: "Server Room Perusahaan",
        description: "Ruangan server dengan perangkat teknologi terkini",
        category: "teknologi",
        image: "https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
    },
    {
        id: 4,
        title: "Pelatihan Teknisi",
        description: "Sesi pelatihan untuk teknisi jaringan baru",
        category: "kegiatan",
        image: "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
    },
    {
        id: 5,
        title: "Instalasi Jaringan Wireless",
        description: "Pemasangan tower wireless untuk jaringan internet",
        category: "instalasi",
        image: "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
    },
    {
        id: 6,
        title: "Kepuasan Pelanggan",
        description: "Pelanggan puas dengan layanan internet kami",
        category: "pelanggan",
        image: "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
    }
];

// Fungsi untuk menyimpan data ke localStorage
function saveGalleryData(galleryItems) {
    try {
        localStorage.setItem(GALLERY_STORAGE_KEY, JSON.stringify(galleryItems));
        return true;
    } catch (error) {
        console.error('Error saving gallery data:', error);
        return false;
    }
}

// Fungsi untuk mengambil data dari localStorage
function getGalleryData() {
    try {
        const storedData = localStorage.getItem(GALLERY_STORAGE_KEY);
        if (storedData) {
            return JSON.parse(storedData);
        }
        // Jika belum ada data, simpan data default
        saveGalleryData(defaultGalleryItems);
        return defaultGalleryItems;
    } catch (error) {
        console.error('Error loading gallery data:', error);
        return defaultGalleryItems;
    }
}

// Fungsi untuk menambah item baru
function addGalleryItem(item) {
    const galleryItems = getGalleryData();
    const newId = galleryItems.length > 0 ? Math.max(...galleryItems.map(i => i.id)) + 1 : 1;
    const newItem = {
        ...item,
        id: newId
    };
    galleryItems.push(newItem);
    saveGalleryData(galleryItems);
    return newItem;
}

// Fungsi untuk update item
function updateGalleryItem(id, updatedItem) {
    const galleryItems = getGalleryData();
    const index = galleryItems.findIndex(item => item.id === id);
    
    if (index !== -1) {
        galleryItems[index] = { ...galleryItems[index], ...updatedItem, id: id };
        saveGalleryData(galleryItems);
        return true;
    }
    return false;
}

// Fungsi untuk menghapus item
function deleteGalleryItem(id) {
    const galleryItems = getGalleryData();
    const newGalleryItems = galleryItems.filter(item => item.id !== id);
    saveGalleryData(newGalleryItems);
    return true;
}

// Fungsi untuk mencari item berdasarkan filter
function searchGalleryItems(searchTerm, category = 'all') {
    const galleryItems = getGalleryData();
    
    return galleryItems.filter(item => {
        const matchesCategory = category === 'all' || item.category === category;
        const matchesSearch = !searchTerm || 
            item.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            item.description.toLowerCase().includes(searchTerm.toLowerCase());
        
        return matchesCategory && matchesSearch;
    });
}

// Export fungsi jika menggunakan ES6 modules
// export {
//     getGalleryData,
//     addGalleryItem,
//     updateGalleryItem,
//     deleteGalleryItem,
//     searchGalleryItems,
//     saveGalleryData
// };