<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQ - SiBantu</title>
<link rel="stylesheet" href="./CSS_RAFI/index.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
<script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
</head>
<body class="flex flex-col min-h-screen">
    
<section class="faq-page flex-grow">
    <!-- Header Section -->
    <header>    
        <div class="navbar">
            <a class="logo" href="index.php">SiBantu</a>
            <div class="nav-center">
                <a href="index.php#testimonial-section">Testimonial</a>
                <a href="index.php#explore-section">Katalog</a>
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
            <a href="index.php#testimonial-section">Testimonial</a>
            <a href="index.php#explore-section">Katalog</a>
            <a href="login_page.php">Masuk</a>
        </div>
    </header>

    <!-- FAQ Section -->
    <section class="faq-section px-4 mx-auto max-w-screen-xl py-24">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-center text-gray-900 md:text-5xl lg:text-6xl">Pertanyaan yang Sering Diajukan</h1>
        <p class="mb-8 text-lg font-normal text-center text-gray-600 lg:text-xl sm:px-16 lg:px-48">Temukan jawaban untuk pertanyaan yang paling sering diajukan tentang SiBantu.</p>

        <div class="faq-items space-y-6">
            <!-- FAQ Item 1 -->
            <div class="faq-item bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300 ease-in-out cursor-pointer" onclick="toggleFAQ(this)">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Apa yang Harus Dilakukan Jika Pembayaran Gagal?</h3>
                    <button class="faq-toggle text-2xl font-bold text-gray-800 focus:outline-none transform transition-all duration-300 ease-in-out">
                        <span class="plus">+</span><span class="minus hidden">×</span>
                    </button>
                </div>
                <p class="faq-description mt-2 text-gray-600 hidden">
                    Jika Anda mengalami masalah dengan pembayaran, silakan hubungi layanan pelanggan kami.
                </p>
            </div>

            <!-- FAQ Item 2 -->
            <div class="faq-item bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300 ease-in-out cursor-pointer" onclick="toggleFAQ(this)">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Bagaimana Cara Menghubungi Dukungan?</h3>
                    <button class="faq-toggle text-2xl font-bold text-gray-800 focus:outline-none transform transition-all duration-300 ease-in-out">
                        <span class="plus">+</span><span class="minus hidden">×</span>
                    </button>
                </div>
                <p class="faq-description mt-2 text-gray-600 hidden">
                    Anda dapat menghubungi dukungan kami melalui email di support@sibantu.com atau telepon di +62 812 3456 7890.
                </p>
            </div>

            <!-- FAQ Item 3 -->
            <div class="faq-item bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300 ease-in-out cursor-pointer" onclick="toggleFAQ(this)">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Bagaimana Cara Mengatur Ulang Kata Sandi?</h3>
                    <button class="faq-toggle text-2xl font-bold text-gray-800 focus:outline-none transform transition-all duration-300 ease-in-out">
                        <span class="plus">+</span><span class="minus hidden">×</span>
                    </button>
                </div>
                <p class="faq-description mt-2 text-gray-600 hidden">
                    Untuk mengatur ulang kata sandi, buka halaman login dan klik "Lupa Kata Sandi" untuk menerima tautan pengaturan ulang.
                </p>
            </div>

            <!-- FAQ Item 4 -->
            <div class="faq-item bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300 ease-in-out cursor-pointer" onclick="toggleFAQ(this)">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Apakah SiBantu Tersedia di Semua Kota?</h3>
                    <button class="faq-toggle text-2xl font-bold text-gray-800 focus:outline-none transform transition-all duration-300 ease-in-out">
                        <span class="plus">+</span><span class="minus hidden">×</span>
                    </button>
                </div>
                <p class="faq-description mt-2 text-gray-600 hidden">
                    Ya, SiBantu tersedia di berbagai kota besar di Indonesia. Anda dapat memeriksa ketersediaan layanan di kota Anda melalui aplikasi.
                </p>
            </div>

            <!-- FAQ Item 5 -->
            <div class="faq-item bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300 ease-in-out cursor-pointer" onclick="toggleFAQ(this)">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Apakah Saya Bisa Membatalkan Pesanan?</h3>
                    <button class="faq-toggle text-2xl font-bold text-gray-800 focus:outline-none transform transition-all duration-300 ease-in-out">
                        <span class="plus">+</span><span class="minus hidden">×</span>
                    </button>
                </div>
                <p class="faq-description mt-2 text-gray-600 hidden">
                    Ya, Anda dapat membatalkan pesanan dalam waktu 30 menit setelah melakukan pemesanan. Hubungi dukungan kami untuk bantuan lebih lanjut.
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
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold">Tautan Cepat</h3>
                <nav class="mt-4 space-y-2">
                    <a href="index.php" class="block hover:underline">Beranda</a>
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
    function toggleFAQ(card) {
        const description = card.querySelector('.faq-description');
        const button = card.querySelector('.faq-toggle');
        description.classList.toggle('hidden');
        button.querySelector('.plus').classList.toggle('hidden');
        button.querySelector('.minus').classList.toggle('hidden');
    }

    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>
</body>
</html>
