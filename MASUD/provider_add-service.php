<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tambah Layanan</title>
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
                        <a href="provider_add-service.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Service</a>
                        <a href="provider_transaction-history.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Riwayat Transaksi</a>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <!-- Tabs -->
                    <div class="flex gap-6 border-b pb-4 mb-6">
                        <a href="User_dashboard.php" class="text-gray-500 hover:text-blue-600">Profil Penyedia Jasa</a>
                        <a href="User_daftar.php" class="text-gray-500 hover:text-blue-600">User Review</a>
                    </div>

                    <h2 class="text-2xl font-bold mb-6">Tambah Layanan</h2>

                    <?php if (isset($error_message)): ?>
                        <div class="text-red-500 mb-4"><?= htmlspecialchars($error_message) ?></div>
                    <?php endif; ?>
                    <?php if (isset($success_message)): ?>
                        <div class="text-green-500 mb-4"><?= htmlspecialchars($success_message) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <label for="service_name" class="block text-gray-700">Nama Layanan</label>
                            <input type="text" name="service_name" id="service_name" class="w-full p-3 border rounded-md" required>
                        </div>

                        <div class="mb-4">
                            <label for="service_description" class="block text-gray-700">Deskripsi Layanan</label>
                            <textarea name="service_description" id="service_description" class="w-full p-3 border rounded-md" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="service_price" class="block text-gray-700">Harga Layanan</label>
                            <input type="number" name="service_price" id="service_price" class="w-full p-3 border rounded-md" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="bg-gray-800 text-white px-6 py-3 rounded-full shadow hover:bg-gray-700 transition">
                                Tambah Layanan
                            </button>
                        </div>
                    </form>


                    <!-- Daftar -->
                    <?php
                    $services = [
                        [
                            'name' => 'Jasa Kebersihan Rumah',
                            'description' => 'Membersihkan rumah secara menyeluruh',
                            'price' => 150000,
                            'status' => 'pending'
                        ],
                        [
                            'name' => 'Jasa Tukang Kebun',
                            'description' => 'Merawat dan membersihkan taman',
                            'price' => 200000,
                            'status' => 'approved'
                        ],
                        [
                            'name' => 'Jasa Cuci Pakaian',
                            'description' => 'Mencuci dan menyetrika pakaian',
                            'price' => 100000,
                            'status' => 'approved'
                        ]
                    ];
                    ?>

                    <div class="space-y-4">
                        <?php foreach ($services as $index => $service): ?>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg"><?= htmlspecialchars($service['name']) ?></h4>
                                    <p class="text-gray-600 mt-1"><?= htmlspecialchars($service['description']) ?></p>
                                    <p class="text-gray-800 font-medium mt-2">Rp <?= number_format($service['price'], 0, ',', '.') ?></p>
                                </div>
                                <div class="ml-4 flex flex-col items-end space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <!-- Status Badge -->
                                        <span class="px-4 py-1 rounded-full text-sm font-medium
                                            <?= $service['status'] === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            Status: <?= ucfirst($service['status']) ?>
                                        </span>
                                        
                                        <!-- Hapus Button -->
                                        <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?');">
                                            <input type="hidden" name="delete_service" value="<?= $index ?>">
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
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
