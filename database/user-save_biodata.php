<?php
session_start();
include('db_connect.php'); // defines $conn using mysqli_connect()

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id     = $_SESSION['user_id'];
$name        = $_POST['name'] ?? '';
$birthdate   = $_POST['birthdate'] ?? '';
$gender      = $_POST['gender'] ?? '';
$email       = $_POST['email'] ?? '';
$phone       = $_POST['phone'] ?? '';
$profile_picture = null;

if (!empty($_POST['cropped_image'])) {
    $base64_image = $_POST['cropped_image'];
    if (strpos($base64_image, 'base64,') !== false) {
        $base64_image = explode('base64,', $base64_image)[1];
    }
    $profile_picture = $base64_image;
}

// Check if user info exists
$sql_check = "SELECT * FROM user_information WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_info = mysqli_fetch_assoc($result);

if ($user_info) {
    // Update
    $sql_update = "UPDATE user_information 
                   SET name = ?, birthdate = ?, gender = ?, email = ?, phone = ?";
    if ($profile_picture) {
        $sql_update .= ", profile_picture = ?";
    }
    $sql_update .= " WHERE user_id = ?";

    if ($profile_picture) {
        $stmt = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt, "ssssssi", $name, $birthdate, $gender, $email, $phone, $profile_picture, $user_id);
    } else {
        $stmt = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt, "sssssi", $name, $birthdate, $gender, $email, $phone, $user_id);
    }

    mysqli_stmt_execute($stmt);
    echo json_encode(["success" => true, "message" => "User information updated."]);
} else {
    // Insert
    $sql_insert = "INSERT INTO user_information 
                   (user_id, name, birthdate, gender, email, phone";
    if ($profile_picture) {
        $sql_insert .= ", profile_picture";
    }
    $sql_insert .= ") VALUES (?, ?, ?, ?, ?, ?";
    if ($profile_picture) {
        $sql_insert .= ", ?";
    }
    $sql_insert .= ")";

    if ($profile_picture) {
        $stmt = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt, "issssss", $user_id, $name, $birthdate, $gender, $email, $phone, $profile_picture);
    } else {
        $stmt = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt, "isssss", $user_id, $name, $birthdate, $gender, $email, $phone);
    }

    mysqli_stmt_execute($stmt);
    echo json_encode(["status" => "success", "message" => "New user information created."]);
}
?>
