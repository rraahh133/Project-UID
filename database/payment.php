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
        
        // === 2. ADMIN APPROVES OR REJECTS ===
    } elseif ($action === 'Admin Approves or Rejects an Order') {
        if ($_SESSION['usertype'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
            exit;
        }

        $order_id = $_POST['order_id'] ?? '';
        $status = $_POST['status'] ?? ''; // accepted or rejected

        if (!$order_id || !in_array($status, ['verified proof', 'declined'])) {
            echo json_encode(['success' => false, 'message' => 'Parameter tidak valid']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Status pesanan diperbarui oleh admin.']);

    // === 3. SELLER SUBMITS PROOF OF WORK ===
    } elseif ($action === 'Seller Submits Proof of Work') {
        if ($_SESSION['usertype'] !== 'seller') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
            exit;
        }

        $order_id = $_POST['order_id'] ?? '';
        $file = $_FILES['work_proof'];

        if (!$order_id || !$file) {
            echo json_encode(['success' => false, 'message' => 'ID pesanan dan file dibutuhkan.']);
            exit;
        }
        $file_tmp = $file['tmp_name'];
        $file_content = file_get_contents($file_tmp);
        $file_base64 = base64_encode($file_content);

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("UPDATE orders SET seller_proof = ?, status = 'proof_submitted' WHERE id = ?");
            $stmt->bind_param("si", $file_base64, $order_id);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Bukti kerja berhasil dikirim. Menunggu validasi admin.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengunggah bukti kerja.']);
        }

    // === 4. ADMIN MARKS AS COMPLETED ===  
    } else if ($action === 'Admin Marks Order as Completed') {
        if ($_SESSION['usertype'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
            exit;
        }

        $order_id = $_POST['order_id'] ?? '';

        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'ID pesanan tidak valid']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Pesanan ditandai sebagai selesai.']);
    // === 5. seller CANCELS ORDER ===
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
    // === 6. seller take order ===
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
    
    } else if ($action === 'JobDone') {
        if ($_SESSION['usertype'] !== 'seller') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
            exit;
        }
        $order_id = $_POST['order_id'] ?? '';
        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'ID pesanan tidak valid']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Pesanan Diselesaikan oleh penjual.']);

    } else {
        echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenali']);
    } 

} else {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid']);
}
?>
