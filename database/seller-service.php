<?php
include('db_connect.php');
session_start();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
} else {
    header('Content-Type: text/html; charset=UTF-8');
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'add') {
        $nama_layanan = isset($_POST['nama_layanan']) ? htmlspecialchars(trim($_POST['nama_layanan'])) : '';
        $harga = isset($_POST['harga']) ? trim($_POST['harga']) : '';
        $deskripsi = isset($_POST['deskripsi']) ? htmlspecialchars(trim($_POST['deskripsi'])) : '';
        $imageBase64 = isset($_POST['image_base64']) ? $_POST['image_base64'] : '';
        $kategori = isset($_POST['kategori']) ? htmlspecialchars(trim($_POST['kategori'])) : ''; // ✅ NEW

        if (empty($nama_layanan) || empty($harga) || empty($kategori)) {
            echo json_encode(['success' => false, 'message' => 'Nama layanan, harga, dan kategori wajib diisi']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("
                INSERT INTO seller_service (user_id, service_name, service_description, service_price, service_image, service_type)
                VALUES (:user_id, :nama_layanan, :deskripsi, :harga, :service_image, :kategori)
            ");

            $success = $stmt->execute([
                ':user_id' => $user_id,
                ':nama_layanan' => $nama_layanan,
                ':deskripsi' => $deskripsi,
                ':harga' => $harga,
                ':service_image' => $imageBase64,
                ':kategori' => $kategori
            ]);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Layanan berhasil ditambahkan' : 'Gagal menambahkan layanan'
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    
    if ($action === 'getservice') {
        try {
            if (empty($user_id)) {
                echo json_encode(['success' => false, 'message' => 'User ID is required']);
                exit;
            }
    
            $stmt = $pdo->prepare("
                SELECT *
                FROM seller_service
                WHERE user_id = :user_id
            ");
            $stmt->execute([':user_id' => $user_id]);
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($services) {
                echo json_encode(['success' => true, 'services' => $services]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No services found']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    else if ($action === 'update') {
        $requiredFields = ['service_id', 'nama_layanan', 'harga', 'deskripsi'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi']);
                exit;
            }
        }

        $service_id = intval($_POST['service_id']);
        $nama_layanan = htmlspecialchars(trim($_POST['nama_layanan']));
        $harga = trim($_POST['harga']);
        $deskripsi = htmlspecialchars(trim($_POST['deskripsi']));

        try {
            $stmt = $pdo->prepare("
                UPDATE seller_service 
                SET nama_layanan = :nama_layanan, harga = :harga, deskripsi = :deskripsi
                WHERE id = :id AND user_id = :user_id
            ");
            $success = $stmt->execute([
                ':nama_layanan' => $nama_layanan,
                ':harga' => $harga,
                ':deskripsi' => $deskripsi,
                ':id' => $service_id,
                ':user_id' => $user_id
            ]);

            echo json_encode(['success' => $success, 'message' => $success ? 'Layanan berhasil diperbarui' : 'Gagal memperbarui layanan']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Delete Service
    else if ($action === 'delete') {
        if (!isset($_POST['service_id']) || empty($_POST['service_id'])) {
            echo json_encode(['success' => false, 'message' => 'Service ID wajib diisi']);
            exit;
        }
    
        $service_id = intval($_POST['service_id']);
    
        try {
            $stmt = $pdo->prepare("
                DELETE FROM seller_service WHERE service_id = :service_id AND user_id = :user_id
            ");
            $success = $stmt->execute([
                ':service_id' => $service_id,
                ':user_id' => $user_id
            ]);
    
            echo json_encode(['success' => $success, 'message' => $success ? 'Layanan berhasil dihapus' : 'Gagal menghapus layanan']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid']);
}
