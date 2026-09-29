<?php
// get-staff.php
session_start();
require_once 'config/database.php';

// Cek login
if (!isset($_SESSION['admin_logged_in'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

// Get staff ID
$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM staff WHERE id = ?");
        $stmt->execute([$id]);
        $staff = $stmt->fetch();
        
        if ($staff) {
            echo json_encode(['success' => true, 'staff' => $staff]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Staff not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
}
?>