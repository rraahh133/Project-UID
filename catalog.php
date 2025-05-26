<?php
require './database/service_functions.php';
$kategori = isset($_POST['kategori']) ? $_POST['kategori'] : 'all';
$services = getFilteredServices($conn, $kategori);
$user = getUserData($conn);
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
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

    <header class="bg-white shadow-md">
        <div class="flex items-center justify-between px-6 py-4">
            <!-- Logo -->
            <a href="index.php" class="text-xl md:text-2xl font-bold text-black mr-auto no-underline">SiBantu</a>

            <!-- Center Nav -->
            <!-- Right Nav -->
            <div class="hidden md:flex items-center gap-4">
                <?php if ($user): ?>
                    <div id="user-menu-container" class="relative">
                        <button 
                            type="button"
                            class="flex items-center gap-2 text-gray-800 hover:text-blue-600 focus:outline-none"
                            id="user-menu-button"
                            aria-expanded="false"
                            aria-haspopup="true"
                            onclick="document.getElementById('user-dropdown').classList.toggle('hidden')"
                        >
                            <span><?= htmlspecialchars($user['username']) ?></span>
                            <img src="<?= $user['profile_picture'] ? 'data:image/jpeg;base64,' . $user['profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" 
                                alt="Profile Picture"
                                class="w-10 h-10 rounded-full border-2 border-white"
                            />
                            <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="user-dropdown" class="absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                                <a href="./ZIDAN/User_dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                                <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">Logout</a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="relative group">
                        <a href="#" class="text-gray-700 hover:text-blue-600">Masuk <span class="ml-1">&#8250;</span></a>
                        <div class="absolute hidden group-hover:block bg-white shadow-md mt-2 py-2 rounded-md z-10">
                            <a href="login_user.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Masuk as User</a>
                            <a href="login_seller.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Masuk as Seller</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Hamburger -->
            <div class="md:hidden">
                <button onclick="toggleMenu()" class="text-gray-700 text-2xl focus:outline-none">&#9776;</button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden flex flex-col gap-4 px-6 py-4 bg-white">
            <a href="#testimonial-section" class="text-gray-700 hover:text-blue-500">Testimonial</a>
            <a href="#explore-section" class="text-gray-700 hover:text-blue-500">Katalog</a>
            <?php if ($user): ?>
                <a href="./ZIDAN/User_dashboard.php" class="text-gray-700 hover:text-blue-500">Dashboard</a>
                <a href="logout.php" class="text-gray-700 hover:text-blue-500">Logout</a>
            <?php else: ?>
                <div class="relative">
                    <button onclick="document.getElementById('mobile-login-dropdown').classList.toggle('hidden')" class="text-gray-700 hover:text-blue-500 flex items-center">
                        Masuk <span class="ml-1">&#8250;</span>
                    </button>
                    <div id="mobile-login-dropdown" class="hidden flex flex-col mt-2 space-y-2">
                        <a href="login_user.php" class="text-gray-700 hover:text-blue-500">Masuk as User</a>
                        <a href="login_seller.php" class="text-gray-700 hover:text-blue-500">Masuk as Seller</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </header>


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

    <?php require './User/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>

    // Filter Function kategori
    function setCategory(kategori) {
        document.getElementById('kategoriInput').value = kategori;
        document.getElementById('filterForm').submit();
    }

    // disable resubmit on refresh page
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</body>
</html> 