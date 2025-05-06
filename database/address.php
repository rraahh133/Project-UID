<?php
include('db_connect.php');

session_start();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    header('Content-Type: application/json');
} else {
    header('Content-Type: text/html; charset=UTF-8');
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $nama_penerima = $_POST['nama_penerima'];
        $nomor_telepon = $_POST['nomor_telepon'];
        $alamat_lengkap = $_POST['alamat_lengkap'];
        $keterangan = $_POST['keterangan'];

        if (empty($nama_penerima) || empty($nomor_telepon) || empty($alamat_lengkap)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }

        // Insert query
        $stmt = $pdo->prepare("
            INSERT INTO user_addresses (user_id, nama_penerima, nomor_telepon, alamat_lengkap, keterangan)
            VALUES (:user_id, :nama_penerima, :nomor_telepon, :alamat_lengkap, :keterangan)
        ");
        $success = $stmt->execute([
            ':user_id' => $userid,
            ':nama_penerima' => $nama_penerima,
            ':nomor_telepon' => $nomor_telepon,
            ':alamat_lengkap' => $alamat_lengkap,
            ':keterangan' => $keterangan
        ]);

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save address']);
        }
    } else if ($action === 'update') {
        $requiredFields = ['address_id', 'nama_penerima', 'nomor_telepon', 'alamat_lengkap', 'keterangan'];
        
        // Validate required fields
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
                exit;
            }
        }

        // Sanitize and retrieve POST data
        $id = $_POST['address_id'];
        $nama_penerima = trim($_POST['nama_penerima']);
        $nomor_telepon = trim($_POST['nomor_telepon']);
        $alamat_lengkap = trim($_POST['alamat_lengkap']);
        $keterangan = trim($_POST['keterangan']) ?: null;
        $user_id = $_SESSION['user_id'] ?? null;

        // Check if user is logged in
        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            exit;
        }

        try {
            // Prepare and execute update query
            $stmt = $pdo->prepare("UPDATE user_addresses SET nama_penerima = ?, nomor_telepon = ?, alamat_lengkap = ?, keterangan = ? WHERE id = ? AND user_id = ?");
            $success = $stmt->execute([$nama_penerima, $nomor_telepon, $alamat_lengkap, $keterangan, $id, $user_id]);
            echo json_encode(['success' => $success, 'message' => $success ? 'Address updated successfully' : 'Failed to update address']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else if ($action === 'delete') {
        if (isset($_POST['address_id']) && !empty($_POST['address_id'])) {
            $addressId = $_POST['address_id'];
            $userId = $_SESSION['user_id'] ?? null; // Assuming user_id is stored in session
    
            // Ensure user is logged in
            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                exit;
            }
    
            // Delete query
            $stmt = $pdo->prepare("DELETE FROM user_addresses WHERE id = :id AND user_id = :user_id");
            $success = $stmt->execute([
                ':id' => $addressId,
                ':user_id' => $userId
            ]);
    
            // Return success or failure response
            echo json_encode(['success' => $success, 'message' => $success ? 'Address deleted successfully' : 'Failed to delete address']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Address ID is required']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
