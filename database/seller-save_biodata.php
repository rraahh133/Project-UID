<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id       = $_SESSION['user_id'];
$name          = $_POST['name'] ?? null;
$birthdate     = $_POST['birthdate'] ?? null;
$gender        = $_POST['gender'] ?? null;
$email         = $_POST['email'] ?? null;
$phone         = $_POST['phone'] ?? null;
$bank          = $_POST['bank'] ?? null;
$norekening    = $_POST['no_rekening'] ?? null;

$profile_picture = null;

// Base64 Image Handling
if (!empty($_POST['cropped_image'])) {
    $base64_image = $_POST['cropped_image'];
    if (strpos($base64_image, 'base64,') !== false) {
        $base64_image = explode('base64,', $base64_image)[1];
    }
    $profile_picture = $base64_image;
}

// Check if user info exists
$sql_check = "SELECT * FROM seller_information WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_info = mysqli_fetch_assoc($result);

if ($user_info) {
    // Update
    $sql_update = "UPDATE seller_information 
                   SET name = ?, birthdate = ?, gender = ?, email = ?, phone = ?, bank_name = ?, no_rekening = ?";
    if ($profile_picture) {
        $sql_update .= ", profile_picture = ?";
        $sql_update .= " WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt, "ssssssssi", $name, $birthdate, $gender, $email, $phone, $bank, $norekening, $profile_picture, $user_id);
    } else {
        $sql_update .= " WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt, "sssssssi", $name, $birthdate, $gender, $email, $phone, $bank, $norekening, $user_id);
    }

    mysqli_stmt_execute($stmt);
    echo json_encode(["success" => true, "message" => "User information updated."]);

} else {
    // Insert
    $sql_insert = "INSERT INTO seller_information 
                   (user_id, name, birthdate, gender, email, phone, bank_name, no_rekening";
    if ($profile_picture) {
        $sql_insert .= ", profile_picture";
    }
    $sql_insert .= ") VALUES (?, ?, ?, ?, ?, ?, ?, ?";
    if ($profile_picture) {
        $sql_insert .= ", ?";
    }
    $sql_insert .= ")";

    if ($profile_picture) {
        $stmt = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt, "issssssss", $user_id, $name, $birthdate, $gender, $email, $phone, $bank, $norekening, $profile_picture);
    } else {
        $stmt = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt, "isssssss", $user_id, $name, $birthdate, $gender, $email, $phone, $bank, $norekening);
    }

    mysqli_stmt_execute($stmt);
    echo json_encode(["success" => true, "message" => "New user information created."]);
}
?>
