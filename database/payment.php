<?php
include('db_connect.php');
session_start();

// Set JSON response header
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
} else {
    header('Content-Type: text/html; charset=UTF-8');
}

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User belum login']);
    exit;
}

$current_user_id = $_SESSION['user_id'];

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    $order_id = $_POST['order_id'] ?? '';
    $action = $_POST['action'] ?? ''; // 'take' or 'cancel'
    $seller_id = $_SESSION['user_id'];

    // === 1. Buyer Creates an Order ===
    if ($action === 'Buyer Creates an Order') {
        $seller_id = $_POST['seller_id'] ?? '';
        $service_id = $_POST['service_id'] ?? '';
        $file = $_FILES['payment_proof'] ?? null;

        if (!$seller_id || !$service_id || !$file) {
            echo json_encode(['success' => false, 'message' => 'Semua data harus diisi.']);
            exit;
        }

        $file_tmp = $file['tmp_name'];
        $file_content = file_get_contents($file_tmp);
        $file_base64 = base64_encode($file_content);

        $stmt = $conn->prepare("INSERT INTO orders (customer_id, seller_id, service_id, price, payment_proof, status) VALUES (?, ?, ?, ?, ?, 'pending proof')");
        $stmt->bind_param("iiids", $current_user_id, $seller_id, $service_id, $price, $file_base64);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Pesanan berhasil dibuat. Menunggu validasi admin. Akan dialihkan ke halaman status pesanan.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data pesanan.']);
        }
    // === 2. Buyer Cancel Order ===
    } else if ($action === 'CancelJobSeller') {
        if ($_SESSION['usertype'] !== 'seller') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
            exit;
        }

        $order_id = $_POST['order_id'] ?? '';

        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'ID pesanan tidak valid']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE orders SET status = 'declined' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Pesanan dibatalkan oleh penjual.']);
    // === 3. Seller Accept Order ===
    } else if ($action === 'takeJobSeller') {
        if ($_SESSION['usertype'] !== 'seller') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
            exit;
        }
        $order_id = $_POST['order_id'] ?? '';
        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'ID pesanan tidak valid']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE orders SET status = 'Work On Progress' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Pesanan diambil oleh penjual.']);
    
    // === 4. Seller Mark Job as Done ===
    } else if ($action === 'JobDone') {
        $order_id = $_POST['order_id'] ?? '';
        $image_base64 = $_POST['image_base64'] ?? '';
        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'ID pesanan tidak valid']);
            exit;
        }

        if ($image_base64 === '') {
            // Check if seller_proof already exists in database
            $stmt = $conn->prepare("SELECT seller_proof FROM orders WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->bind_result($existing_proof);
            $stmt->fetch();
            $stmt->close();

            if ($existing_proof) {
            // Do not update seller_proof, only update status
                $stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
                $stmt->bind_param("i", $order_id);
                $stmt->execute();
                echo json_encode(['success' => true, 'message' => 'Pesanan Diselesaikan oleh User.']);
                exit;
            }
        }

        // If image_base64 is provided, update both status and seller_proof
        $stmt = $conn->prepare("UPDATE orders SET status = 'completed', seller_proof = ? WHERE id = ?");
        $stmt->bind_param("si", $image_base64, $order_id);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Pesanan Diselesaikan oleh penjual.']);
    
        // === 5. Buyer Complain Job ===
    } else if ($action === 'JobKomplain') {
        if ($_SESSION['usertype'] !== 'customer') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
            exit;
        }
        $order_id = $_POST['order_id'] ?? '';
        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'ID pesanan tidak valid']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE orders SET status = 'komplain' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Pesanan DiKomplain. Seller Akan menghubungi anda.']);
    
    } else if ($action === 'JobRating') {
        if ($_SESSION['usertype'] !== 'customer') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
            exit;
        }
        $order_id = $_POST['order_id'] ?? '';
        $rating = $_POST['rating'] ?? '';
        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'ID pesanan tidak valid']);
            exit;
        }
        $stmt = $conn->prepare("UPDATE orders SET rating = ? WHERE id = ?");
        $stmt->bind_param("ii", $rating, $order_id);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Terima kasih atas penilaian anda.']);

    } else {
        echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenali']);
    } 

} else {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid']);
}
?>
