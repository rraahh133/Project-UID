<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tentang Kami - SiBantu</title>
<link rel="stylesheet" href="./assets/css/index.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
<script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
</head>
<body class="flex flex-col min-h-screen">

<section class="about-page flex-grow">
    <!-- Header Section -->
    <header>
        <div class="navbar">
            <a class="logo" href="index.php">SiBantu</a>
            <div class="nav-center">
            </div>
            <div class="nav-right">
                <a href="login_page.php">Masuk</a>
            </div>
            <!-- Hamburger icon for mobile -->
            <div class="hamburger" onclick="toggleMenu()">
                &#9776;
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu">
            <a href="login_page.php">Masuk</a>
        </div>
    </header>

    <!-- Tentang Kami Section -->
    <section class="about-section px-4 mx-auto max-w-screen-xl py-24">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-center text-gray-900 md:text-5xl lg:text-6xl">Tentang Kami</h1>
        <p class="mb-8 text-lg font-normal text-center text-gray-600 lg:text-xl sm:px-16 lg:px-48">
            SiBantu Suruh adalah platform yang menyediakan berbagai jasa suruhan online, mulai dari membersihkan hunian, merawat hewan peliharaan, antar/jemput, pindahan, hingga perbaikan peralatan rumah tangga.
        </p>

        <div class="about-content space-y-6">
            <!-- Misi Kami -->
            <div class="about-item bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300 ease-in-out">
                <h3 class="text-xl font-semibold text-gray-800">Misi Kami</h3>
                <p class="text-gray-600 mt-2">
                    Misi kami adalah untuk mempermudah tugas sehari-hari dengan menyediakan layanan terpercaya yang dapat Anda andalkan. Apakah itu pembersihan rumah, perawatan hewan, atau layanan pindahan, kami siap membantu Anda dengan penyedia layanan terbaik.
                </p>
            </div>

            <!-- Visi Kami -->
            <div class="about-item bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300 ease-in-out">
                <h3 class="text-xl font-semibold text-gray-800">Visi Kami</h3>
                <p class="text-gray-600 mt-2">
                    Kami bertujuan untuk menjadi platform terdepan untuk layanan online, menyediakan cara yang mudah dan terpercaya bagi pengguna untuk menemukan bantuan untuk tugas sehari-hari mereka dengan kenyamanan.
                </p>
            </div>

            <!-- Layanan Kami -->
            <div class="about-item bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300 ease-in-out">
                <h3 class="text-xl font-semibold text-gray-800">Layanan Kami</h3>
                <p class="text-gray-600 mt-2">
                    SiBantu Suruh menawarkan berbagai layanan, termasuk:
                    <ul class="list-disc pl-5 mt-2">
                        <li>Fotografi</li>
                        <li>Graphic Design</li>
                        <li>Pembuatan Website</li>
                        <li>Kebersihan</li>
                        <li>Perbaikan Peralatan Rumah Tangga</li>
                    </ul>
                </p>
            </div>
        </div>
    </section>
</section>

<!-- Footer Section -->
<footer class="bg-gray-800 text-white py-8 mt-auto">
    <div class="px-4 mx-auto max-w-screen-xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Logo and Description -->
            <div>
                <h2 class="text-2xl font-bold">SiBantu</h2>
                <p class="mt-4 text-sm">
                    Mitra terpercaya Anda untuk layanan sehari-hari. Hubungi kami kapan saja, di mana saja.
                </p>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold">Quick Links</h3>
                <nav class="mt-4 space-y-2">
                    <a href="index.php" class="block hover:underline">Home</a>
                    <a href="faq.php" class="block hover:underline">FAQ</a>
                </nav>
            </div>
            
            <!-- Contact Us -->
            <div>
                <h3 class="text-lg font-semibold">Hubungi Kami</h3>
                <nav class="mt-4 space-y-2">
                    <a href="mailto:support@sibantu.com" class="block hover:underline">support@sibantu.com</a>
                    <a href="tel:+6281234567890" class="block hover:underline">+62 812 3456 7890</a>
                </nav>
            </div>
        </div>
    </div>
</footer>

<script>
    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>
</body>
</html>
