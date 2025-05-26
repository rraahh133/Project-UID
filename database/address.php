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

        $stmt = mysqli_prepare($conn, "
            INSERT INTO user_addresses (user_id, nama_penerima, nomor_telepon, alamat_lengkap, keterangan)
            VALUES (?, ?, ?, ?, ?)
        ");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "issss", $userid, $nama_penerima, $nomor_telepon, $alamat_lengkap, $keterangan);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save address']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
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

        $id = $_POST['address_id'];
        $nama_penerima = trim($_POST['nama_penerima']);
        $nomor_telepon = trim($_POST['nomor_telepon']);
        $alamat_lengkap = trim($_POST['alamat_lengkap']);
        $keterangan = trim($_POST['keterangan']) ?: null;

        $user_id = $_SESSION['user_id'] ?? null;

        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            exit;
        }

        $stmt = mysqli_prepare($conn, "UPDATE user_addresses SET nama_penerima = ?, nomor_telepon = ?, alamat_lengkap = ?, keterangan = ? WHERE id = ? AND user_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssii", $nama_penerima, $nomor_telepon, $alamat_lengkap, $keterangan, $id, $user_id);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo json_encode(['success' => $success, 'message' => $success ? 'Address updated successfully' : 'Failed to update address']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
        }

    } else if ($action === 'delete') {
        if (isset($_POST['address_id']) && !empty($_POST['address_id'])) {
            $addressId = $_POST['address_id'];
            $userId = $_SESSION['user_id'] ?? null;

            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                exit;
            }

            $stmt = mysqli_prepare($conn, "DELETE FROM user_addresses WHERE id = ? AND user_id = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ii", $addressId, $userId);
                $success = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                echo json_encode(['success' => $success, 'message' => $success ? 'Address deleted successfully' : 'Failed to delete address']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Address ID is required']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
