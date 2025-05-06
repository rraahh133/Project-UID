<?php
session_start();
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'seller') {
    http_response_code(403);
    exit("Access denied.");
}

?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Rating Pengguna</title>
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
                        <a href="provider_add-service.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Tambah Layanan</a>
                        <a href="provider_transaction-history.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Riwayat Transaksi</a>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <!-- Tabs -->
                    <div class="flex gap-6 border-b pb-4 mb-6">
                        <a href="provider-dashboard.php" class="text-gray-500 hover:text-blue-600">Profil Penyedia Jasa</a>
                        <a href="provider_user-reviews.php" class="text-blue-600 font-semibold">User Review</a>
                    </div>

                    <!-- Review Content -->
                    <div class="space-y-6">
                        <!-- Rating Average -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-2xl font-bold mb-4">Rating Rata-rata</h2>
                            <div class="flex items-center gap-4">
                                <div class="text-4xl font-bold text-gray-800">4.5</div>
                                <div class="flex flex-col">
                                    <div class="text-yellow-500 text-3xl">⭐⭐⭐⭐</div>
                                    <div class="text-sm text-gray-600">Berdasarkan 10 ulasan</div>
                                </div>
                            </div>
                        </div>

                        <!-- User Reviews -->
                        <div class="space-y-4">
                            <h2 class="text-2xl font-bold mb-4">Ulasan Pengguna</h2>
                            <div class="space-y-4">
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h3 class="font-semibold">Budi Santoso</h3>
                                            <div class="text-yellow-500">⭐⭐⭐⭐⭐</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">Sangat memuaskan!</p>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h3 class="font-semibold">Ani Wijaya</h3>
                                            <div class="text-yellow-500">⭐⭐⭐⭐</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">Layanan cepat dan ramah.</p>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h3 class="font-semibold">Dewi Putri</h3>
                                            <div class="text-yellow-500">⭐⭐⭐⭐</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">Harga terjangkau dan hasil memuaskan.</p>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h3 class="font-semibold">Rudi Hartono</h3>
                                            <div class="text-yellow-500">⭐⭐⭐⭐⭐</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">Profesional dan tepat waktu.</p>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h3 class="font-semibold">Siti Aminah</h3>
                                            <div class="text-yellow-500">⭐⭐⭐⭐</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">Sangat direkomendasikan!</p>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h3 class="font-semibold">Ahmad Fadillah</h3>
                                            <div class="text-yellow-500">⭐⭐⭐⭐</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">Pelayanan yang sangat baik.</p>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h3 class="font-semibold">Maya Sari</h3>
                                            <div class="text-yellow-500">⭐⭐⭐⭐⭐</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">Akan menggunakan jasa ini lagi.</p>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h3 class="font-semibold">Joko Susilo</h3>
                                            <div class="text-yellow-500">⭐⭐⭐⭐</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">Sangat membantu dan ramah.</p>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h3 class="font-semibold">Linda Wijaya</h3>
                                            <div class="text-yellow-500">⭐⭐⭐⭐⭐</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">Layanan yang luar biasa.</p>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <img src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h3 class="font-semibold">Rina Melati</h3>
                                            <div class="text-yellow-500">⭐⭐⭐⭐</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">Sangat puas dengan hasilnya.</p>
                                </div>
                            </div>
                        </div>
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