<?php
require './database/service_functions.php';
$user = getUserData($conn);
$dashboardLink = './User/user_dashboard.php';
if (($user['usertype'] ?? '') === 'seller') {
    $dashboardLink = './Seller/provider-dashboard.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<link rel="stylesheet" href="./assets/css/index.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
<script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
</head>
<body>
    
<section class="landing-page">

    <header>
        <div class="navbar">
            <a class="logo" href="index.php">SiBantu</a>
            <div class="nav-center">
                <a href="#testimonial-section">Testimonial</a>
                <a href="#explore-section">Katalog</a>  
            </div>
            <div class="nav-right">
                <?php if ($user): ?>
                    <div id="user-menu-container" class="relative inline-block text-left">
                        <button type="button" 
                                class="flex items-center gap-2 text-white focus:outline-none" 
                                id="user-menu-button" 
                                aria-expanded="false" 
                                aria-haspopup="true"
                                onclick="document.getElementById('user-dropdown').classList.toggle('hidden')">
                            <span><?= htmlspecialchars($user['username']) ?></span>
                            <img src="<?= $user['info_profile_picture'] ? 'data:image/jpeg;base64,' . $user['info_profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" 
                                alt="Profile Picture" 
                                class="rounded-full w-10 h-10 border-2 border-white" />
                            <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="user-dropdown" class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                                <a href="<?= htmlspecialchars($dashboardLink) ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">
                                    Settings
                                </a>
                                <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="dropdown">
                        <a href="auth.php">Masuk <span class="arrow">&#8250;</span></a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Hamburger icon for mobile -->
            <div class="hamburger" onclick="toggleMenu()">
                &#9776;
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu">
            <a href="#testimonial-section">Testimonial</a>
            <a href="#explore-section">Katalog</a>
            <div class="mobile-dropdown">
                <a href="auth.php">Masuk <span class="arrow">&#8250;</span></a>
            </div>
        </div>
    </header>

    <section class="bg-center bg-no-repeat bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/conference.jpg')] bg-gray-700 bg-blend-multiply">
        <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-56">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">ANDA ORANG SIBUK?</h1>
            <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">HUBUNGI KAMI UNTUK MEMBANTU ANDA</p>
            <div class="flex flex-col sm:flex-row sm:justify-center gap-3 sm:gap-3">
                <a href="form.php" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                    Custom Form
                </a>
                <a href="aboutus.php" class="inline-flex justify-center hover:text-gray-900 items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg border border-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-400">
                    About Us
                </a>  
            </div>
        </div>  
    </section>


    <section class="explore-section" id="explore-section">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Jasa yang Kami Jual</h2>
            <p class="text-gray-600">Pilih layanan yang sesuai kebutuhan Anda</p>
        </div>
        <div class="flex justify-center items-center w-full">
            <!-- Horizontal Scrollable Container -->
            <div class="relative w-full max-w-6xl">
                <!-- Wrapper to show only 1 card -->
                <div class="overflow-hidden">
                    <div id="scrollContainer" class="flex gap-4 p-4 transition-transform duration-300">
                        <!-- Card 1 -->
                        <div class="flex-none w-64 sm:w-48 md:w-64 bg-white rounded-lg shadow-md">
                            <img src="https://th.bing.com/th/id/OIP.JuU_TgpPZcrlqR576NAcDQHaJD?rs=1&pid=ImgDetMain" alt="Card 1" class="w-full h-40 rounded-t-lg object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">Jasa Fotografi</h3>
                                <p class="text-gray-600 text-sm">Prewed, pernikahan, DLL</p>
                                <button onclick="setCategory('Fotografi')" class="mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Lihat Kategori Ini
                                </button>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="flex-none w-64 sm:w-48 md:w-64 bg-white rounded-lg shadow-md">
                            <img src="https://a.storyblok.com/f/96206/6720x4480/9408ef5b87/ucd-pa-graphic-design-art-2.jpg" alt="Card 2" class="w-full h-40 rounded-t-lg object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">Jasa Graphic Design</h3>
                                <p class="text-gray-600 text-sm">Membuat logo, poster, DLL</p>
                                <button onclick="setCategory('Design')" class="mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Lihat Kategori Ini
                                </button>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="flex-none w-64 sm:w-48 md:w-64 bg-white rounded-lg shadow-md">
                            <img src="https://assets-global.website-files.com/6410ebf8e483b5bb2c86eb27/6410ebf8e483b53d6186fc53_ABM%20College%20Web%20developer%20main.jpg" alt="Card 3" class="w-full h-40 rounded-t-lg object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">Jasa Pembuatan Website</h3>
                                <p class="text-gray-600 text-sm">untuk bisnis atau portofolio pribadi</p>
                                <button onclick="setCategory('Other')" class="mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Lihat Kategori Ini
                                </button>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="flex-none w-64 sm:w-48 md:w-64 bg-white rounded-lg shadow-md">
                            <img src="https://www.megabaja.co.id/storage/2023/04/Kategori-Rumah-3-1600x900.jpg" alt="Card 4" class="w-full h-40 rounded-t-lg object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">Jasa Kebersihan</h3>
                                <p class="text-gray-600 text-sm">membersihkan rumah, kantor, DLL</p>
                                <button onclick="setCategory('Kebersihan')" class="mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Lihat Kategori Ini
                                </button>
                            </div>
                        </div>
                        <!-- Card 5 -->
                        <div class="flex-none w-64 sm:w-48 md:w-64 bg-white rounded-lg shadow-md">
                            <img src="https://th.bing.com/th/id/OIP.W-jupXOluFK_QaeDeYe-EwHaHa?rs=1&pid=ImgDetMain" 
                                 alt="Custom Request Image" 
                                 class="w-full h-40 rounded-t-lg object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">Custom Request</h3>
                                <p class="text-gray-600 text-sm">Jika tidak ada yang jasa yg perlukan</p>
                                <a href="form.php" class="mt-2 inline-block px-4 py-2 bg-blue-500 text-white text-center rounded hover:bg-blue-600">
                                    Lihat Kategori Ini
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tombol See All -->
                <div class="flex justify-center mt-6">
                    <a href="catalog.php" class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        Lihat Semua Layanan
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>  

    </section>

    <section class="testimonial-section" id="testimonial-section">
        <div class="testimonial-content">
            <h2 class="testimonial-heading">Testimonial</h2>
            <div class="testimonial-grid">
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325489716988084235/image.png?ex=683d1dbe&is=683bcc3e&hm=15df4b321a3b6ea20386ba7c7a7a9ef13506ee68b9a6b2d6fc08be1ff43ce5a0&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Reja</h3>
                        <p class="testimonial-comment">"SiBantu benar-benar membantu saya dalam banyak hal. Aplikasinya simpel dan mudah digunakan, jadi saya bisa lebih fokus ke pekerjaan saya tanpa banyak gangguan."</p>
                    </div>
                </article>
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325489810927779951/image.png?ex=683d1dd5&is=683bcc55&hm=c92fe01f629327c6725d5dbf8787ac3f7f2707594162dbb2ee0710405c4ba5d2&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Rutz</h3>
                        <p class="testimonial-comment">"Gak nyangka banget ada aplikasi yang sepraktis ini. Saya jadi bisa lebih gampang atur waktu dan pekerjaan. Benar-benar ngebantu banget!"</p>
                    </div>
                </article>
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325489968507916369/image.png?ex=683d1dfa&is=683bcc7a&hm=4da9bed855e44af70d46c6aa7ac29b4b0192bb71b27aa605226bf98d7437e9d9&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Yoyo</h3>
                        <p class="testimonial-comment">"Aplikasi ini bener-bener membantu banget, jadi gak perlu ribet lagi. Semua jadi lebih cepat dan praktis. Saya suka banget!"</p>
                    </div>
                </article>
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325491190853799956/image.png?ex=683d1f1e&is=683bcd9e&hm=647ad613464b2f84ef5c2eb4302819013227dd58732109d7997931416e17e58f&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Natan</h3>
                        <p class="testimonial-comment">"Ni Sibantu Gacor Parah Bagus Banget coy bisa bantu bisa bikin krepes😍😍😍😍"</p>
                    </div>
                </article>
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325491517728755713/WhatsApp_Image_2025-01-05_at_22.49.23_a706d67b.jpg?ex=683d1f6c&is=683bcdec&hm=2e1de645605a9392e67fce214150a26acbb4d6f7e5050b4c9f5a8b74abfaf69b&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Medek</h3>
                        <p class="testimonial-comment">"SiBantu benar-benar mengubah hidup saya! Dengan bantuan mereka, saya bisa menyelesaikan proyek dengan cepat dan efisien. Layanan yang sangat direkomendasikan! 🌟"</p>
                    </div>
                </article>
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325492660890243192/WhatsApp_Image_2025-01-05_at_22.50.15_4eb75ed3.jpg?ex=683d207c&is=683bcefc&hm=6c01814238b0e14deeca2478564b34655051488cd17d2ec0505a8c34565e7418&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Bintoro</h3>
                        <p class="testimonial-comment">"Bagus"</p>
                    </div>
                </article>
            </div>
        </div>
    </section>
    

    <!-- Footer Section -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="px-4 mx-auto max-w-screen-xl">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Logo and Description -->
                <div>
                    <h2 class="text-2xl font-bold">SiBantu</h2>
                    <p class="mt-4 text-sm">
                        Mitra andalan Anda untuk layanan sehari-hari. Hubungi kami kapan saja, di mana saja.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold">Quick Links</h3>
                    <nav class="mt-4 space-y-2">
                        <a href="index.php" class="block hover:underline">Home</a>
                        <a href="faq.php" class="block hover:underline">FAQ</a>
                    </nav>
                </div>
                
                <!-- Contact Us -->
                <div>
                    <h3 class="text-lg font-semibold">Contact Us</h3>
                    <nav class="mt-4 space-y-2">
                        <a href="mailto:support@sibantu.com" class="block hover:underline">support@sibantu.com</a>
                        <a href="tel:+6281234567890" class="block hover:underline">+62 812 3456 7890</a>
                    </nav>
                </div>
            </div>
        </div>
    </footer>

</section>
<script src="./assets/js/script.js"></script>
<script>
    function setCategory(kategori) {
    const form = document.createElement('form');
    form.method = 'POST'; // or use 'GET' if catalog.php reads from $_GET
    form.action = '/Projek/catalog.php';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'kategori';
    input.value = kategori;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
</html>