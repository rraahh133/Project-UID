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
            user_information.profile_picture AS info_profile_picture,

            seller_information.email AS seller_info_email,
            seller_information.name AS seller_info_name,
            seller_information.birthdate AS seller_info_birthdate,
            seller_information.gender AS seller_info_gender,
            seller_information.phone AS seller_info_phone,
            seller_information.profile_picture AS seller_info_profile_picture,
            seller_information.bank_name AS seller_info_bank,
            seller_information.no_rekening AS seller_info_no_rekening

        FROM users
        LEFT JOIN user_information ON users.user_id = user_information.user_id
        LEFT JOIN seller_information ON users.user_id = seller_information.user_id
        WHERE users.user_id = ?
    ";

    $results = executeQuery($conn, $sql, 'i', [$user_id]);
    $userData = $results[0] ?? null;

    if ($userData) {
        // Fetch user addresses from user_addresses table
        $sqlAddresses = "
            SELECT 
                id,
                nama_penerima,
                nomor_telepon,
                alamat_lengkap,
                keterangan
            FROM user_addresses
            WHERE user_id = ?
        ";
        $addresses = executeQuery($conn, $sqlAddresses, 'i', [$user_id]);
        $userData['addresses'] = $addresses ?: [];
    }

    return $userData;
}

function fetchServiceBySeller($conn, $service_id, $seller_user_id) {
    $sql = "
        SELECT s.service_id, 
        s.service_name, 
        s.service_description, 
        s.service_price, 
        s.status, 
        s.service_image, 
        s.service_type,  
        i.name AS provider_name, 
        i.phone AS provider_number,
        i.bank_name AS provider_bank,
        i.no_rekening AS provider_account_number
        FROM seller_service s
        JOIN seller_information i ON s.user_id = i.user_id
        WHERE s.service_id = ? AND s.user_id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $service_id, $seller_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
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