<?php
include ('../database/db_connect.php'); // Include your PDO connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_user.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            users.user_id,
            users.username,
            users.email AS user_email,           -- alias to avoid conflict
            users.usertype,
            users.name,
            users.birthdate,
            users.gender,
            users.phone,
            user_information.email AS info_email,  -- alias here
            user_information.name AS info_name,
            user_information.birthdate AS info_birthdate,
            user_information.gender AS info_gender,
            user_information.phone AS info_phone,
            user_information.profile_picture
        FROM users
        LEFT JOIN user_information ON users.user_id = user_information.user_id
        WHERE users.user_id = :id
    ");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: ../login_user.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
    exit;
}
?>


<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Biodata Diri
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-gray-800 shadow-md p-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">
                <a href="./../index.php">SiBantu</a>
            </h1>
            <div class="flex items-center gap-4">
                <i class="fas fa-bell text-white text-lg"></i>
                <i class="fas fa-envelope text-white text-lg"></i>
                <img src="<?= $user['profile_picture'] ? 'data:image/jpeg;base64,' . $user['profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" class="rounded-full w-10 h-10 border-2 border-white" />
            </div>
        </header>

        <div class="flex flex-1 flex-col md:flex-row">
            <!-- Sidebar -->
            <aside class="w-full md:w-64 bg-white p-6 shadow-md">
                <div class="flex items-center gap-3 mb-6">
                    <img src="<?= $user['profile_picture'] ? 'data:image/jpeg;base64,' . $user['profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" class="rounded-full w-12 h-12">
                    <div>
                        <h2 class="text-lg font-semibold"><?= htmlspecialchars($user['name'] ?? 'User') ?></h2>
                        <p class="text-gray-500 text-sm">Member Silver</p>
                    </div>
                </div>

                <!-- Dropdown -->
                <div>
                    <button onclick="toggleDropdown('transaksiDropdown')" class="w-full bg-gray-100 px-4 py-2 rounded flex justify-between items-center text-gray-700 font-medium hover:bg-gray-200">
                        Transaksi
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div id="transaksiDropdown" class="hidden mt-2">
                        <a href="User_status.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Status Pembayaran</a>
                        <a href="User_Riwayat Transaksi.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Riwayat Transaksi</a>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <!-- Tabs -->
                    <div class="flex gap-6 border-b pb-4 mb-6">
                        <a href="User_dashboard.php" class="text-blue-600 font-semibold">Biodata Diri</a>
                        <a href="User_daftar.php" class="text-gray-500 hover:text-blue-600">Daftar Alamat</a>
                    </div>

                    <!-- Profile Header -->
                    <div class="flex items-center gap-8 mb-8">
                        <img src="<?= $user['profile_picture'] ? 'data:image/jpeg;base64,' . $user['profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" class="w-36 h-36 rounded-full border shadow">
                        <div>
                            <h2 class="text-2xl font-bold"><?= htmlspecialchars($user['name'] ?? 'User') ?></h2>
                            <p class="text-gray-600"><?= htmlspecialchars($user['user_email'] ?? '-') ?></p>
                        </div>
                    </div>

                    <!-- Biodata Fields -->
                    <div class="space-y-4 text-gray-700">
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Nama</span>
                            <span><?= htmlspecialchars($user['name'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Tanggal Lahir</span>
                            <span><?= htmlspecialchars($user['birthdate'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Jenis Kelamin</span>
                            <span><?= htmlspecialchars($user['gender'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Nomor HP</span>
                            <span><?= htmlspecialchars($user['phone'] ?? '-') ?></span>
                        </div>
                    </div>

                    <!-- Edit Button -->
                    <div class="text-center mt-8">
                        <a href="update_form.php" class="inline-block bg-gray-800 text-white px-6 py-3 rounded-full shadow hover:bg-gray-700 transition">
                            Edit Biodata
                        </a>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-10 mt-auto">
            <div class="max-w-screen-xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div>
                    <h2 class="text-xl font-bold mb-2">SiBantu</h2>
                    <p class="text-sm">Mitra andalan Anda untuk layanan sehari-hari. Hubungi kami kapan saja, di mana saja.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2">Quick Links</h3>
                    <ul class="space-y-1 text-sm">
                        <li><a href="index.php" class="hover:underline">Home</a></li>
                        <li><a href="faq.php" class="hover:underline">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2">Contact Us</h3>
                    <ul class="space-y-1 text-sm">
                        <li><a href="mailto:support@sibantu.com" class="hover:underline">support@sibantu.com</a></li>
                        <li><a href="tel:+6281234567890" class="hover:underline">+62 812 3456 7890</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    <script>
        function toggleDropdown(id) {
            var dropdown = document.getElementById(id);
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }
    </script>
</body>

</html>