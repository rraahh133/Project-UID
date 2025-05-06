<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup and Login</title>
    <style>
        /* Basic styles for better form appearance */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        input[type="text"], input[type="password"], select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Signup Form -->
<div class="form-container">
    <h2>Signup</h2>
    <form action="login_register.php" method="POST">
        <input type="text" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <select name="usertype" required>
            <option value="customer">Customer</option>
            <option value="seller">Seller</option>
        </select>
        <input type="submit" name="signup" value="Signup">
    </form>
    <?php if (isset($error_message)) { echo '<div class="message">' . $error_message . '</div>'; } ?>
</div>

<!-- Login Form -->
<div class="form-container" style="margin-top: 20px;">
    <h2>Login</h2>
    <form action="login_register.php" method="POST">
        <input type="text" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>
</div>

</body>
</html>
