<?php
require './database/service_functions.php';
$kategori = isset($_POST['kategori']) ? $_POST['kategori'] : 'all';
$services = getFilteredServices($kategori);
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Layanan - SiBantu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .service-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="index.php" class="text-2xl font-bold text-blue-600">SiBantu</a>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-gray-600 hover:text-blue-600">Home</a>
                    <a href="catalog.php" class="text-blue-600">Katalog</a>
                    <a href="auth.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Katalog Layanan</h1>
        
        <!-- Filter Section -->

        <form method="POST" id="filterForm">
            <input type="hidden" name="kategori" id="kategoriInput" value="all">
            <div class="mb-8">
                <div class="flex flex-wrap gap-4">
                    <button type="button" onclick="setCategory('all')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Semua</button>
                    <button type="button" onclick="setCategory('Desain')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Desain</button>
                    <button type="button" onclick="setCategory('Fotografi')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Fotografi</button>
                    <button type="button" onclick="setCategory('Teknologi')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Teknologi</button>
                    <button type="button" onclick="setCategory('Kebersihan')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Kebersihan</button>
                    <button type="button" onclick="setCategory('Other')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Other</button>
                </div>
            </div>
        </form>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($services as $service): ?>
                <div class="bg-white rounded-xl shadow-md service-card overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2"></div>
                    <img src="<?= htmlspecialchars($service['service_image']) ?>" alt="<?= htmlspecialchars($service['service_name']) ?>" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($service['service_name']) ?></h3>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm"><?= htmlspecialchars($service['service_type']) ?></span>
                        </div>
                        
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <!-- Assuming no rating data, you can later join/compute it -->
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="far fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="ml-2 text-gray-600">(No rating)</span>
                        </div>
                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($service['service_description']) ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-blue-600">Rp <?= number_format($service['service_price'], 0, ',', '.') ?></span>
                            <a href="payment.php?service=<?= urlencode($service['service_name']) ?>&price=<?= urlencode($service['service_price']) ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">SiBantu</h3>
                    <p class="text-gray-400">Solusi layanan terpercaya untuk kebutuhan Anda</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <p class="text-gray-400">Email: support@sibantu.com</p>
                    <p class="text-gray-400">Telp: +62 812 3456 7890</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function setCategory(kategori) {
        document.getElementById('kategoriInput').value = kategori;
        document.getElementById('filterForm').submit();
    }
    </script>
</body>
</html> 