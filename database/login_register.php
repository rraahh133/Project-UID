<?php
include('db_connect.php'); // Assumes $conn is defined as a mysqli connection

session_start();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    header('Content-Type: application/json');
} else {
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
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "Email already taken. Please Use Another Email"]);
            exit;
        }

        // Register user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (email, password, usertype) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $hashed_password, $usertype);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Registration successful!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Something went wrong. Please try again."]);
        }

        $stmt->close();

    } else if (isset($_POST['login'])) {
        // LOGIN SECTION
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['usertype'] = $user['usertype']; // auto-detected

                if ($user['usertype'] === 'customer') {
                    $redirectUrl = './User/user_dashboard.php';
                } else if ($user['usertype'] === 'seller') {
                    $redirectUrl = './Seller/provider-dashboard.php';
                } else if ($user['usertype'] === 'admin') {
                    $redirectUrl = './ADMIN/admin.php';
                } else {
                    $redirectUrl = './'; // default fallback
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
            echo json_encode(["status" => "error", "message" => "User not registered."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid request."]);
    }
}
?>
