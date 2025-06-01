<?php
include('db_connect.php'); // Menghubungkan ke database
session_start();

// Set header JSON jika lewat AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
} else {
    header('Content-Type: text/html; charset=UTF-8');
}

// Cek login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User belum login']);
    exit;
}

$current_user_id = $_SESSION['user_id'];

// Pastikan metode POST dan action tersedia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // === EDIT USER ===
    if ($action === 'edituser') {
        $user_id = $_POST['user_id'] ?? '';
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? '';

        if (empty($user_id) || empty($name) || empty($email) || empty($role)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            exit;
        }

        $stmt1 = $conn->prepare("UPDATE users SET email = ?, usertype = ? WHERE user_id = ?");
        $stmt1->bind_param("ssi", $email, $role, $user_id);
        $result1 = $stmt1->execute();

        $stmt2 = $conn->prepare("UPDATE user_information SET name = ? WHERE user_id = ?");
        $stmt2->bind_param("si", $name, $user_id);
        $result2 = $stmt2->execute();

        if ($result1 && $result2) {
            echo json_encode(['success' => true, 'message' => 'Data pengguna berhasil diperbarui']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data pengguna']);
        }

    // === DELETE USER ===
    } elseif ($action === 'deleteuser') {
        $user_id = $_POST['user_id'] ?? '';
        if (empty($user_id)) {
            echo json_encode(['success' => false, 'message' => 'ID pengguna tidak diberikan']);
            exit;
        }

        // Hapus dari user_information terlebih dahulu
        $stmt1 = $conn->prepare("DELETE FROM user_information WHERE user_id = ?");
        $stmt1->bind_param("i", $user_id);
        $result1 = $stmt1->execute();
        // Hapus dari users
        $stmt2 = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt2->bind_param("i", $user_id);
        $result2 = $stmt2->execute();
        if ($result1 && $result2) {
            echo json_encode(['success' => true, 'message' => 'Pengguna berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus pengguna']);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenali']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid']);
}
