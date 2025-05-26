<?php
include ('../database/db_connect.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
    SELECT users.*, user_information.*
    FROM users
    LEFT JOIN user_information ON users.user_id = user_information.user_id
    WHERE users.user_id = :id
    ");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: ../auth.php");
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
    <title>Riwayat Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                        <a href="user_status.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Status Pembayaran</a>
                        <a href="user_RiwayatTransaksi.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Riwayat Transaksi</a>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <!-- Tabs -->
                    <div class="flex gap-6 border-b pb-4 mb-6">
                        <a href="user_dashboard.php" class="text-gray-500 hover:text-blue-600">Biodata Diri</a>
                        <a href="user_daftar.php" class="text-gray-500 hover:text-blue-600">Daftar Alamat</a>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-2xl font-bold mb-6">Riwayat Transaksi</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">No</th>
                                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">ID Transaksi</th>
                                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Jenis Jasa</th>
                                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Tanggal</th>
                                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Harga</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 text-sm text-gray-700">1</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">TRX001</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">Jasa Membersihkan Rumah</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">2024-10-01</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">Rp100.000,00</td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td class="py-3 px-4 text-right font-semibold text-gray-700" colspan="4">Total Transaksi</td>
                                        <td class="py-3 px-4 font-semibold text-gray-700">Rp100.000,00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <?php require 'footer.php'; ?>

    </div>

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