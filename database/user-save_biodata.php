<?php
session_start();
include ('../database/db_connect.php'); // Ensure this file contains the PDO connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user data
$user_id = $_SESSION['user_id'];
echo $user_id;

// Get the POST data from the form
$name = $_POST['name'];
$birthdate = $_POST['birthdate'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Handle cropped image input (base64 string)
$profile_picture = null;
if (!empty($_POST['cropped_image'])) {
    $base64_image = $_POST['cropped_image'];

    // Strip metadata if included (e.g., "data:image/jpeg;base64,")
    if (strpos($base64_image, 'base64,') !== false) {
        $base64_image = explode('base64,', $base64_image)[1];
    }

    $profile_picture = $base64_image;
}

// Check if user exists in user_information table
$stmt = $pdo->prepare("SELECT * FROM user_information WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_info = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user_info) {
    // User exists in user_information, perform update
    $query = "UPDATE users SET email = :email";
    $query = "UPDATE user_information SET name = :name, birthdate = :birthdate, gender = :gender, email = :email, phone = :phone";
    if ($profile_picture) {
        $query .= ", profile_picture = :profile_picture";
    }
    $query .= " WHERE user_id = :user_id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':birthdate', $birthdate);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    if ($profile_picture) {
        $stmt->bindParam(':profile_picture', $profile_picture);
    }
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["status" => "success", "message" => "User information updated."]);
} else {
    // User does not exist in user_information, create new record
    $query = "INSERT INTO user_information (user_id, name, birthdate, gender, email, phone";
    if ($profile_picture) {
        $query .= ", profile_picture";
    }
    $query .= ") VALUES (:user_id, :name, :birthdate, :gender, :email, :phone";
    if ($profile_picture) {
        $query .= ", :profile_picture";
    }
    $query .= ")";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':birthdate', $birthdate);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    if ($profile_picture) {
        $stmt->bindParam(':profile_picture', $profile_picture);
    }
    $stmt->execute();

    echo json_encode(["status" => "success", "message" => "New user information created."]);
}
?>
