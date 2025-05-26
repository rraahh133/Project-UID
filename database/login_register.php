<?php
include('db_connect.php');

session_start();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    header('Content-Type: application/json');
} else {
    // Don't set the header for normal page requests
    header('Content-Type: text/html; charset=UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['signup'])) {
        // SIGNUP SECTION
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $usertype = $_POST['usertype'];

        if ($password !== $confirm_password) {
            echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
            exit;
        }

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "Email already taken. Please Use Another Email"]);
            exit;
        }

        // Register user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password, usertype) VALUES (:email, :password, :usertype)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':usertype', $usertype);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Registration successful!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Something went wrong. Please try again."]);
        }

    } else if (isset($_POST['login'])) {
        // LOGIN SECTION
        $email = $_POST['email'];
        $password = $_POST['password'];
        $usertype = $_POST['usertype']; // Added usertype

        // Prepare statement to fetch user by email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND usertype = :usertype");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':usertype', $usertype); // Bind usertype parameter
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                session_regenerate_id(true); // Optional but safer
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['usertype'] = $user['usertype'];
                
                // Send success response and redirect
                if ($user['usertype'] == 'customer') {
                    $redirectUrl = './User/user_dashboard.php';
                } else if ($user['usertype'] == 'seller') {
                    $redirectUrl = './Seller/provider-dashboard.php';
                }
    
                echo json_encode([
                    "status" => "success",
                    "message" => "Login successful!",
                    "redirect" => $redirectUrl
                ]);
                
            } else {
                echo json_encode(["status" => "error", "message" => "Incorrect password."]);
            }   
        } else {
            echo json_encode(["status" => "error", "message" => "No user found with that email."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid request."]);
    }
} else {
}
?>
