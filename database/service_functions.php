<?php
require_once 'db_connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function fetchAvailableServices($conn) {
    $sql = "
        SELECT s.service_id, s.service_name, s.service_description, s.service_price, s.status, s.service_image, i.name AS provider_name
        FROM seller_service s
        JOIN seller_information i ON s.user_id = i.user_id
        WHERE s.status != 'rejected'
        ORDER BY s.service_id DESC
    ";
    return executeQuery($conn, $sql);
}

function getFilteredServices($conn, $kategori = 'all') {
    if ($kategori === 'all') {
        $sql = "SELECT * FROM seller_service";
        return executeQuery($conn, $sql);
    } else {
        $sql = "SELECT * FROM seller_service WHERE service_type = ?";
        return executeQuery($conn, $sql, 's', [$kategori]);
    }
}

function getUserData($conn, $user_id = null) {
    if (!$user_id) {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        $user_id = $_SESSION['user_id'];
    }

    $sql = "
        SELECT 
            users.user_id,
            users.username,
            users.email AS user_email,
            users.usertype,
            users.name,
            users.birthdate,
            users.gender,
            users.phone,
            user_information.email AS info_email,
            user_information.name AS info_name,
            user_information.birthdate AS info_birthdate,
            user_information.gender AS info_gender,
            user_information.phone AS info_phone,
            user_information.profile_picture
        FROM users
        LEFT JOIN user_information ON users.user_id = user_information.user_id
        WHERE users.user_id = ?
    ";

    $results = executeQuery($conn, $sql, 'i', [$user_id]);
    return $results[0] ?? null;
}

function executeQuery($conn, $sql, $types = null, $params = null) {
    if ($types && $params) {
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            return [];
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_query($conn, $sql);
    }

    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}
?>