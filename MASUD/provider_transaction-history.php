<?php
session_start();
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Total Transaksi</title>
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
                        <a href="provider-dashboard.php" class="text-gray-500 hover:text-blue-600">Profil Penyedia Jasa</a>
                        <a href="provider_user-reviews.php" class="text-gray-500 hover:text-blue-600">User Review</a>
                        <a href="provider_transaction-history.php" class="text-blue-600 font-semibold">Riwayat Transaksi</a>
                    </div>

                    <!-- Transaction Content -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-2xl font-bold mb-4">Total Transaksi</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Jumlah Transaksi</h3>
                                    <p class="text-3xl font-bold text-gray-800" id="total-transactions">7</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Total Pendapatan</h3>
                                    <p class="text-3xl font-bold text-gray-800">Rp 225.000</p>
                                </div>
                            </div>

                            <!-- Payment Status Tabs -->
                            <div class="mt-8">
                                <div class="border-b border-gray-200">
                                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="paymentTabs" role="tablist">
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 rounded-t-lg" id="lunas-tab" data-tab="lunas" type="button" role="tab" aria-controls="lunas" aria-selected="true">
                                                Transaksi Lunas
                                            </button>
                                        </li>
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="pending-tab" data-tab="pending" type="button" role="tab" aria-controls="pending" aria-selected="false">
                                                Menunggu Pembayaran
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Tab Content -->
                                <div id="tabContent" class="mt-4">
                                    <!-- Lunas Tab -->
                                    <div id="lunas" class="tab-pane active">
                                        <div class="space-y-4">
                                            <!-- Transaction Item 1 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Pembersihan Rumah</h4>
                                                        <p class="text-sm text-gray-600">Budi Santoso</p>
                                                    </div>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Lunas</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">15 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 50.000</p>
                                                </div>
                                            </div>

                                            <!-- Transaction Item 2 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Perbaikan AC</h4>
                                                        <p class="text-sm text-gray-600">Ani Wijaya</p>
                                                    </div>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Lunas</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">14 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 75.000</p>
                                                </div>
                                            </div>

                                            <!-- Transaction Item 3 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Cuci Karpet</h4>
                                                        <p class="text-sm text-gray-600">Dewi Putri</p>
                                                    </div>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Lunas</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">13 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 35.000</p>
                                                </div>
                                            </div>

                                            <!-- Transaction Item 4 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Perbaikan Listrik</h4>
                                                        <p class="text-sm text-gray-600">Rudi Hartono</p>
                                                    </div>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Lunas</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">12 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 65.000</p>
                                                </div>
                                            </div>

                                            <!-- Transaction Item 5 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Pembersihan Kantor</h4>
                                                        <p class="text-sm text-gray-600">Siti Aminah</p>
                                                    </div>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Lunas</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">11 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 100.000</p>
                                                </div>
                                            </div>

                                            <!-- Transaction Item 6 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Perbaikan Pipa</h4>
                                                        <p class="text-sm text-gray-600">Ahmad Fadillah</p>
                                                    </div>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Lunas</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">10 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 85.000</p>
                                                </div>
                                            </div>

                                            <!-- Transaction Item 7 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Pembersihan Taman</h4>
                                                        <p class="text-sm text-gray-600">Maya Sari</p>
                                                    </div>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Lunas</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">9 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 45.000</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pending Tab -->
                                    <div id="pending" class="tab-pane hidden">
                                        <div class="space-y-4">
                                            <!-- Pending Transaction 1 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Perbaikan Listrik</h4>
                                                        <p class="text-sm text-gray-600">Rudi Hartono</p>
                                                    </div>
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Pembayaran</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">16 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 65.000</p>
                                                </div>
                                            </div>

                                            <!-- Pending Transaction 2 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Pembersihan Kantor</h4>
                                                        <p class="text-sm text-gray-600">Siti Aminah</p>
                                                    </div>
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Pembayaran</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">16 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 100.000</p>
                                                </div>
                                            </div>

                                            <!-- Pending Transaction 3 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Perbaikan Pipa</h4>
                                                        <p class="text-sm text-gray-600">Ahmad Fadillah</p>
                                                    </div>
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Pembayaran</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">15 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 85.000</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[data-tab]');
            const tabContents = document.querySelectorAll('.tab-pane');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('border-blue-600', 'text-blue-600');
                        t.classList.add('border-transparent');
                    });

                    // Add active class to clicked tab
                    tab.classList.remove('border-transparent');
                    tab.classList.add('border-blue-600', 'text-blue-600');

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show selected tab content
                    const tabId = tab.getAttribute('data-tab');
                    document.getElementById(tabId).classList.remove('hidden');
                });
            });

            // Set first tab as active by default
            tabs[0].click();
        });
    </script>
</body>

</html>
