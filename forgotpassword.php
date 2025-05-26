<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Forgot Password | CodingNepal</title>
    <link rel="stylesheet" href="./assets/css/login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Dark background */
            padding-top: 100px;
            transition: all 0.3s ease;
        }

        .modal-content {
            background-color: #fff;
            margin: 0 auto;
            padding: 40px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease;
        }

        .modal h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .modal p {
            color: #555;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .modal .btn-close {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .modal .btn-close:hover {
            background-color: #0056b3;
        }

        /* Animation for modal */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <header>
        <div class="navbar">
            <a class="logo" href="index.php">SiBantu</a>
        </div>
    </header>
    
    <div class="wrapper">
        <div class="title-text">
            <div class="title login">Forgot Password</div>
        </div>
        <div class="form-container">
            <div class="form-inner">
                <form action="#" class="login" id="forgotPasswordForm">
                    <div class="field">
                        <input type="email" placeholder="Enter your email" required>
                    </div>
                    <div class="field btn">
                        <div class="btn-layer"></div>
                        <input type="submit" value="Send Reset Link">
                    </div>
                    <div class="signup-link">
                        Remembered your password? <a href="auth.php">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <div id="resetModal" class="modal">
        <div class="modal-content">
            <h2>Password Reset Link Sent</h2>
            <p>Link reset password sudah terkirim ke email Anda. Silakan cek inbox atau folder spam.</p>
            <button class="btn-close" id="closeModal">Close</button>
        </div>
    </div>

    <script>
        var modal = document.getElementById("resetModal");
        var closeModal = document.getElementById("closeModal");
        var form = document.getElementById("forgotPasswordForm");
        form.onsubmit = function(event) {
            event.preventDefault();
            modal.style.display = "block";
        }
        closeModal.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
