<?php
session_start();
include('../database/db_connect.php'); // This should define $conn (PDO instance)

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get POST data and sanitize
$name       = $_POST['name'] ?? '';
$birthdate  = $_POST['birthdate'] ?? '';
$gender     = $_POST['gender'] ?? '';
$email      = $_POST['email'] ?? '';
$phone      = $_POST['phone'] ?? '';

// Handle cropped image input (base64 string)
$profile_picture = null;
if (!empty($_POST['cropped_image'])) {
    $base64_image = $_POST['cropped_image'];
    if (strpos($base64_image, 'base64,') !== false) {
        $base64_image = explode('base64,', $base64_image)[1];
    }
    $profile_picture = $base64_image;
}

try {
    // Check if user data exists
    $stmt = $conn->prepare("SELECT * FROM user_information WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_info) {
        // Update user information
        $query = "UPDATE user_information 
                  SET name = :name, birthdate = :birthdate, gender = :gender, email = :email, phone = :phone";
        if ($profile_picture) {
            $query .= ", profile_picture = :profile_picture";
        }
        $query .= " WHERE user_id = :user_id";

        $stmt = $conn->prepare($query);
        $params = [
            ':name' => $name,
            ':birthdate' => $birthdate,
            ':gender' => $gender,
            ':email' => $email,
            ':phone' => $phone,
            ':user_id' => $user_id
        ];
        if ($profile_picture) {
            $params[':profile_picture'] = $profile_picture;
        }

        $stmt->execute($params);
        echo json_encode(["status" => "success", "message" => "User information updated."]);
    } else {
        // Insert new user information
        $query = "INSERT INTO user_information 
                  (user_id, name, birthdate, gender, email, phone";
        if ($profile_picture) {
            $query .= ", profile_picture";
        }
        $query .= ") VALUES 
                  (:user_id, :name, :birthdate, :gender, :email, :phone";
        if ($profile_picture) {
            $query .= ", :profile_picture";
        }
        $query .= ")";

        $stmt = $conn->prepare($query);
        $params = [
            ':user_id' => $user_id,
            ':name' => $name,
            ':birthdate' => $birthdate,
            ':gender' => $gender,
            ':email' => $email,
            ':phone' => $phone
        ];
        if ($profile_picture) {
            $params[':profile_picture'] = $profile_picture;
        }

        $stmt->execute($params);
        echo json_encode(["status" => "success", "message" => "New user information created."]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
