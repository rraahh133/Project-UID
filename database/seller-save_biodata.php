<?php
session_start();
include('../database/db_connect.php'); // This should define $conn as the PDO instance

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get the POST data from the form
$name = $_POST['name'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$gender = $_POST['gender'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

// Handle base64-encoded cropped image
$profile_picture = null;
if (!empty($_POST['cropped_image'])) {
    $base64_image = $_POST['cropped_image'];
    if (strpos($base64_image, 'base64,') !== false) {
        $base64_image = explode('base64,', $base64_image)[1];
    }
    $profile_picture = $base64_image;
}

// Check if user exists
$stmt = $conn->prepare("SELECT user_id FROM seller_information WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$isUpdate = $stmt->rowCount() > 0;

if ($isUpdate) {
    // Update existing record
    $query = "UPDATE seller_information SET 
        name = :name,
        birthdate = :birthdate,
        gender = :gender,
        email = :email,
        phone = :phone" .
        ($profile_picture ? ", profile_picture = :profile_picture" : "") .
        " WHERE user_id = :user_id";
} else {
    // Insert new record
    $query = "INSERT INTO seller_information 
        (user_id, name, birthdate, gender, email, phone" . 
        ($profile_picture ? ", profile_picture" : "") . 
        ") VALUES 
        (:user_id, :name, :birthdate, :gender, :email, :phone" . 
        ($profile_picture ? ", :profile_picture" : "") . 
        ")";
}

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':birthdate', $birthdate);
$stmt->bindParam(':gender', $gender);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':phone', $phone);
if ($profile_picture) {
    $stmt->bindParam(':profile_picture', $profile_picture);
}

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => $isUpdate ? "User information updated." : "New user information created."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to save user information."
    ]);
}
?>
