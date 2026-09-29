<?php
// Koneksi database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "cantika_data_prima";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// CREATE - Tambah galeri baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    
    $sql = "INSERT INTO galleries (title, description, category, image, created_at) 
            VALUES ('$title', '$description', '$category', '$image', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Galeri berhasil ditambahkan']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $sql . '<br>' . $conn->error]);
    }
}

// READ - Ambil semua galeri
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'read') {
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';
    
    $sql = "SELECT * FROM galleries";
    if ($category !== 'all') {
        $sql .= " WHERE category = '$category'";
    }
    $sql .= " ORDER BY created_at DESC";
    
    $result = $conn->query($sql);
    $galleries = [];
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $galleries[] = $row;
        }
    }
    
    echo json_encode($galleries);
}

// UPDATE - Update galeri
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    
    $sql = "UPDATE galleries SET 
            title = '$title',
            description = '$description',
            category = '$category',
            image = '$image',
            updated_at = NOW()
            WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Galeri berhasil diperbarui']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
}

// DELETE - Hapus galeri
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];
    
    $sql = "DELETE FROM galleries WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Galeri berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
}

$conn->close();
?>