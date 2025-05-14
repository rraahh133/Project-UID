<?php
include('db_connect.php');
session_start();

// JSON response headers
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
} else {
    header('Content-Type: text/html; charset=UTF-8');
}

// Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if POST request and action is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    

    // Add Service
    if ($action === 'add') {
        $nama_layanan = isset($_POST['nama_layanan']) ? htmlspecialchars(trim($_POST['nama_layanan'])) : '';
        $harga = isset($_POST['harga']) ? trim($_POST['harga']) : '';
        $deskripsi = isset($_POST['deskripsi']) ? htmlspecialchars(trim($_POST['deskripsi'])) : '';
    
        if (empty($nama_layanan) || empty($harga)) {
            echo json_encode(['success' => false, 'message' => 'Nama layanan dan harga wajib diisi']);
            exit;
        }
    
        try {
            $stmt = $pdo->prepare("
                INSERT INTO seller_service (user_id, service_name, service_description, service_price)
                VALUES (:user_id, :nama_layanan, :deskripsi, :harga)
            ");
            $success = $stmt->execute([
                ':user_id' => $user_id,
                ':nama_layanan' => $nama_layanan,
                ':deskripsi' => $deskripsi,
                ':harga' => $harga
            ]);
    
            if ($success) {
                echo json_encode(['success' => $success, 'message' => $success ? 'Layanan berhasil ditambahkan' : 'Gagal menambahkan layanan']);
            }
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
    
            // Prepare SQL query to fetch services for the given user_id
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
                // No services found
                echo json_encode(['success' => false, 'message' => 'No services found']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Update Service
    else if ($action === 'update') {
        $requiredFields = ['service_id', 'nama_layanan', 'harga', 'deskripsi'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi']);
                exit;
            }
        }

        $service_id = intval($_POST['service_id']); // Ensuring service_id is an integer
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
    
        $service_id = intval($_POST['service_id']); // Ensuring service_id is an integer
    
        try {
            // Prepare delete query
            $stmt = $pdo->prepare("
                DELETE FROM seller_service WHERE service_id = :service_id AND user_id = :user_id
            ");
            $success = $stmt->execute([
                ':service_id' => $service_id,
                ':user_id' => $user_id // Assuming $user_id is already defined elsewhere
            ]);
    
            // Respond with success message
            echo json_encode(['success' => $success, 'message' => $success ? 'Layanan berhasil dihapus' : 'Gagal menghapus layanan']);
        } catch (Exception $e) {
            // Handle any errors
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid']);
}
