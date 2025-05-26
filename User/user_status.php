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
    <title>Status Pembayaran</title>
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

                    <h2 class="text-2xl font-bold mb-6">Status Pembayaran</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">No</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Nomor Transaksi</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Tanggal</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Status</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm text-gray-700">1</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">TRX123456</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">01 Desember 2024</td>
                                    <td class="py-3 px-4 text-sm text-green-500">Lunas</td>
                                    <td class="py-3 px-4 text-sm text-blue-500 cursor-pointer hover:text-blue-700" onclick="openModal('TRX123456', 'Lunas')">Lihat</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm text-gray-700">2</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">TRX654321</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">29 November 2024</td>
                                    <td class="py-3 px-4 text-sm text-red-500">Belum Lunas</td>
                                    <td class="py-3 px-4 text-sm text-blue-500 cursor-pointer hover:text-blue-700" onclick="openModal('TRX654321', 'Belum Lunas')">Lihat</td>
                                </tr>
                            </tbody>
                        </table>
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
    </div>

    <!-- Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden" id="modal">
        <div class="bg-white p-8 rounded-xl shadow-md w-11/12 md:w-1/3">
            <h2 class="text-2xl font-bold mb-6">Detail Pembayaran</h2>
            <div id="transaction-details">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Nomor Transaksi:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="transaction-id"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Jenis Jasa:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="service-type"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Tanggal:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="transaction-date"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Status:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="transaction-status"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Jumlah:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="transaction-amount"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Metode Pembayaran:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="payment-method"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Nama Penerima:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="recipient-name"></td>
                        </tr>
                        <tr class="hidden" id="note-row">
                            <td class="py-3 px-4 text-sm font-semibold text-red-500">Catatan:</td>
                            <td class="py-3 px-4 text-sm text-red-500" id="transaction-note"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4" colspan="2">
                                <img alt="Image of Membersihkan Rumah" class="w-3/4 h-auto rounded-lg mx-auto shadow-md" id="service-image" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-6 text-center">
                <button class="bg-gray-800 text-white px-6 py-3 rounded-full shadow hover:bg-gray-700 transition" onclick="closeModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openModal(transactionId, status) {
            document.getElementById('modal').classList.remove('hidden');
            let details = {
                'TRX123456': {
                    'serviceType': 'Membersihkan Rumah',
                    'date': '01 Desember 2024',
                    'status': 'Lunas',
                    'amount': 'Rp 100.000',
                    'paymentMethod': 'Transfer Bank',
                    'recipientName': 'SiBersih',
                    'note': '',
                    'image': 'https://storage.googleapis.com/a1aa/image/rdmu026VRJKOEZ0OAB3FfkROMOtdqqVGWGXjal8VO2YNmJBKA.jpg'
                },
                'TRX654321': {
                    'serviceType': 'Pindahan Rumah',
                    'date': '29 November 2024',
                    'status': 'Belum Lunas',
                    'amount': 'Rp 500.000',
                    'paymentMethod': 'Transfer Bank',
                    'recipientName': 'Pindahan satset',
                    'note': 'Segera lakukan pembayaran sebelum 10 Desember 2024',
                    'image': 'https://storage.googleapis.com/a1aa/image/4qUQB6qR8kxq4W9V3jJ2R7XqZx1UHtV6L4Qj8MlX6V5T9N8O7R6P5.jpg'
                }
            };

            let transaction = details[transactionId];
            document.getElementById('transaction-id').innerText = transactionId;
            document.getElementById('service-type').innerText = transaction.serviceType;
            document.getElementById('transaction-date').innerText = transaction.date;
            document.getElementById('transaction-status').innerText = transaction.status;
            document.getElementById('transaction-amount').innerText = transaction.amount;
            document.getElementById('payment-method').innerText = transaction.paymentMethod;
            document.getElementById('recipient-name').innerText = transaction.recipientName;
            document.getElementById('service-image').src = transaction.image;
            document.getElementById('service-image').alt = `Image of ${transaction.serviceType}`;

            if (transaction.note) {
                document.getElementById('note-row').classList.remove('hidden');
                document.getElementById('transaction-note').innerText = transaction.note;
            } else {
                document.getElementById('note-row').classList.add('hidden');
            }
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

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