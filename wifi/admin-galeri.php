<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../config/database.php';

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Konfigurasi upload
$uploadDir = '../uploads/';
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
$maxFileSize = 2 * 1024 * 1024; // 2MB

// Buat folder uploads jika belum ada
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// CREATE - Tambah galeri baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    try {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        
        // Handle file upload
        $fileName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            
            // Validasi file
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
            }
            
            if ($file['size'] > $maxFileSize) {
                throw new Exception('Ukuran file terlalu besar. Maksimal 2MB.');
            }
            
            // Generate unique filename
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('gallery_') . '_' . time() . '.' . $fileExt;
            $uploadPath = $uploadDir . $fileName;
            
            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception('Gagal mengupload file.');
            }
        } else {
            throw new Exception('Harap pilih gambar untuk upload.');
        }
        
        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO galleries (title, description, category, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $description, $category, $fileName);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Galeri berhasil ditambahkan',
                'id' => $stmt->insert_id
            ]);
        } else {
            throw new Exception('Gagal menyimpan ke database: ' . $stmt->error);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// READ - Ambil semua galeri
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'read') {
    try {
        $category = isset($_GET['category']) ? $_GET['category'] : 'all';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        $sql = "SELECT * FROM galleries WHERE 1=1";
        
        if ($category !== 'all') {
            $sql .= " AND category = ?";
        }
        
        if (!empty($search)) {
            $sql .= " AND (title LIKE ? OR description LIKE ?)";
            $searchTerm = "%$search%";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $conn->prepare($sql);
        
        if ($category !== 'all' && !empty($search)) {
            $stmt->bind_param("sss", $category, $searchTerm, $searchTerm);
        } elseif ($category !== 'all') {
            $stmt->bind_param("s", $category);
        } elseif (!empty($search)) {
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $galleries = [];
        while ($row = $result->fetch_assoc()) {
            $galleries[] = $row;
        }
        
        echo json_encode($galleries);
        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
            'data' => []
        ]);
    }
}

// GET - Ambil satu galeri
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    try {
        $id = $_GET['id'];
        
        $stmt = $conn->prepare("SELECT * FROM galleries WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($gallery = $result->fetch_assoc()) {
            echo json_encode($gallery);
        } else {
            echo json_encode(null);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// UPDATE - Update galeri
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    try {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $existingImage = $_POST['existing_image'];
        
        // Handle file upload
        $fileName = $existingImage;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            
            // Validasi file
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
            }
            
            if ($file['size'] > $maxFileSize) {
                throw new Exception('Ukuran file terlalu besar. Maksimal 2MB.');
            }
            
            // Generate unique filename
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('gallery_') . '_' . time() . '.' . $fileExt;
            $uploadPath = $uploadDir . $fileName;
            
            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception('Gagal mengupload file.');
            }
            
            // Hapus file lama jika ada
            if ($existingImage && file_exists($uploadDir . $existingImage)) {
                unlink($uploadDir . $existingImage);
            }
        }
        
        // Update ke database
        $stmt = $conn->prepare("UPDATE galleries SET title = ?, description = ?, category = ?, image = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssssi", $title, $description, $category, $fileName, $id);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Galeri berhasil diperbarui'
            ]);
        } else {
            throw new Exception('Gagal update database: ' . $stmt->error);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// DELETE - Hapus galeri
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        $id = $_POST['id'];
        $imageName = $_POST['image'];
        
        // Hapus file gambar jika ada
        if ($imageName && file_exists($uploadDir . $imageName)) {
            unlink($uploadDir . $imageName);
        }
        
        // Hapus dari database
        $stmt = $conn->prepare("DELETE FROM galleries WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Galeri berhasil dihapus'
            ]);
        } else {
            throw new Exception('Gagal menghapus dari database: ' . $stmt->error);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

else {
    echo json_encode([
        'success' => false,
        'message' => 'Action tidak valid'
    ]);
}

$conn->close();
?>