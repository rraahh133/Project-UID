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
    
    if ($action === 'updateServiceStatus') {
        $serviceId = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
        $newStatus = isset($_POST['status']) ? $_POST['status'] : '';

        $validStatuses = ['approved', 'rejected', 'pending'];
        if ($serviceId === 0 || !in_array($newStatus, $validStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE seller_service SET status = :status WHERE service_id = :service_id");
            $success = $stmt->execute([
                ':status' => $newStatus,
                ':service_id' => $serviceId
            ]);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Status updated successfully' : 'Failed to update status'
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }


    if ($action === 'deleteService') {
        $serviceId = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
        if ($serviceId === 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid service ID']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("DELETE FROM seller_service WHERE service_id = :service_id");
            $success = $stmt->execute([':service_id' => $serviceId]);
    
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Service deleted successfully' : 'Failed to delete service'
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
    
    
} else {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid']);
}
