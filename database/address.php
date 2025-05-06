<?php
include('db_connect.php');

session_start();

// Check if the request is an AJAX request
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

// Fetch the user ID from session
$userid = $_SESSION['user_id']; // Assuming the user ID is stored in the session

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

    // Add Address
    if ($_POST['action'] == 'add') {
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
            // Fetch last inserted ID
            $address_id = $pdo->lastInsertId();
            echo json_encode(['success' => true, 'address_id' => $address_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save address']);
        }
    }

    // Update Address
    if ($_POST['action'] == 'update') {
        $id = $_POST['id'];
        $nama_penerima = $_POST['nama_penerima'];
        $nomor_telepon = $_POST['nomor_telepon'];
        $alamat_lengkap = $_POST['alamat_lengkap'];
        $keterangan = $_POST['keterangan'];

        if (empty($nama_penerima) || empty($nomor_telepon) || empty($alamat_lengkap)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }

        // Update query
        $stmt = $pdo->prepare("
            UPDATE user_addresses 
            SET nama_penerima = :nama_penerima, nomor_telepon = :nomor_telepon, alamat_lengkap = :alamat_lengkap, keterangan = :keterangan
            WHERE id = :id AND user_id = :user_id
        ");
        $success = $stmt->execute([
            ':nama_penerima' => $nama_penerima,
            ':nomor_telepon' => $nomor_telepon,
            ':alamat_lengkap' => $alamat_lengkap,
            ':keterangan' => $keterangan,
            ':id' => $id,
            ':user_id' => $userid
        ]);

        echo json_encode(['success' => $success, 'message' => $success ? 'Address updated' : 'Failed to update address']);
    }

    // Delete Address
    if ($_POST['action'] == 'delete') {
        $id = $_POST['id'];

        // Delete query
        $stmt = $pdo->prepare("DELETE FROM user_addresses WHERE id = :id AND user_id = :user_id");
        $success = $stmt->execute([
            ':id' => $id,
            ':user_id' => $userid
        ]);

        echo json_encode(['success' => $success, 'message' => $success ? 'Address deleted' : 'Failed to delete address']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
