<?php 

require_once 'db_connect.php'; // Adjust the path as needed

function fetchAvailableServices($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT s.service_id, s.service_name, s.service_description, s.service_price, s.status, s.service_image, i.name AS provider_name
            FROM seller_service s
            JOIN seller_information i ON s.user_id = i.user_id
            WHERE s.status != 'rejected'
            ORDER BY s.service_id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching services: " . $e->getMessage());
    }
}

function getFilteredServices($kategori = 'all') {
    global $pdo;

    if ($kategori === 'all') {
        $stmt = $pdo->prepare("SELECT * FROM seller_service");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM seller_service WHERE service_type = :kategori");
        $stmt->execute([':kategori' => $kategori]);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
