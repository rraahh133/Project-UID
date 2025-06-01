<?php
include('db_connect.php'); // Koneksi MySQLi harus ada di file ini
session_start();

// Set response type
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
} else {
    header('Content-Type: text/html; charset=UTF-8');
}

// Check user session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}
$user_id = $_SESSION['user_id'];

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Add new service
    if ($action === 'add') {
        $nama_layanan = htmlspecialchars(trim($_POST['nama_layanan'] ?? ''));
        $harga = trim($_POST['harga'] ?? '');
        $deskripsi = htmlspecialchars(trim($_POST['deskripsi'] ?? ''));
        $imageBase64 = $_POST['image_base64'] ?? '';
        $kategori = htmlspecialchars(trim($_POST['kategori'] ?? ''));

        if (empty($nama_layanan) || empty($harga) || empty($kategori)) {
            echo json_encode(['success' => false, 'message' => 'Nama layanan, harga, dan kategori wajib diisi']);
            exit;
        }

        $stmt = $conn->prepare("
            INSERT INTO seller_service (user_id, service_name, service_description, service_price, service_image, service_type)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        if ($stmt === false) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("issdss", $user_id, $nama_layanan, $deskripsi, $harga, $imageBase64, $kategori);
        $success = $stmt->execute();

        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Layanan berhasil ditambahkan' : 'Gagal menambahkan layanan'
        ]);

        $stmt->close();
    }

    // Get services
    elseif ($action === 'getservice') {
        $stmt = $conn->prepare("SELECT * FROM seller_service WHERE user_id = ?");
        if ($stmt === false) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $services = $result->fetch_all(MYSQLI_ASSOC);

        if ($services) {
            echo json_encode(['success' => true, 'services' => $services]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No services found']);
        }

        $stmt->close();
    }

    // Update service
    elseif ($action === 'update') {
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

        $stmt = $conn->prepare("
            UPDATE seller_service 
            SET service_name = ?, service_price = ?, service_description = ?
            WHERE service_id = ? AND user_id = ?
        ");
        if ($stmt === false) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("sdsii", $nama_layanan, $harga, $deskripsi, $service_id, $user_id);
        $success = $stmt->execute();

        echo json_encode(['success' => $success, 'message' => $success ? 'Layanan berhasil diperbarui' : 'Gagal memperbarui layanan']);

        $stmt->close();
    }

    // Delete service
    elseif ($action === 'delete') {
        $service_id = intval($_POST['service_id'] ?? 0);
        if (!$service_id) {
            echo json_encode(['success' => false, 'message' => 'Service ID wajib diisi']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM seller_service WHERE service_id = ? AND user_id = ?");
        if ($stmt === false) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("ii", $service_id, $user_id);
        $success = $stmt->execute();

        echo json_encode(['success' => $success, 'message' => $success ? 'Layanan berhasil dihapus' : 'Gagal menghapus layanan']);

        $stmt->close();
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid']);
}
