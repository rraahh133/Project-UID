<?php
include('./database/db_connect.php'); // Your PDO connection

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = null; // Default if not logged in

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("
            SELECT 
                users.user_id,
                users.username,
                user_information.profile_picture
            FROM users
            LEFT JOIN user_information ON users.user_id = user_information.user_id
            WHERE users.user_id = :id
        ");
        $stmt->execute([':id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle error if you want, or ignore silently here
    }
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
                            <img src="<?= $user['profile_picture'] ? 'data:image/jpeg;base64,' . $user['profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" 
                                alt="Profile Picture" 
                                class="rounded-full w-10 h-10 border-2 border-white" />
                            <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="user-dropdown" class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                                <a href="./User/user_dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                                <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">Logout</a>
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
                                <a href="payment.php?service=Jasa%20Fotografi&price=Rp%20500.000%20-%202.000.000&category=Fotografi" class="mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="flex-none w-64 sm:w-48 md:w-64 bg-white rounded-lg shadow-md">
                            <img src="https://a.storyblok.com/f/96206/6720x4480/9408ef5b87/ucd-pa-graphic-design-art-2.jpg" alt="Card 2" class="w-full h-40 rounded-t-lg object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">Jasa Graphic Design</h3>
                                <p class="text-gray-600 text-sm">Membuat logo, poster, DLL</p>
                                <a href="payment.php?service=Jasa%20Graphic%20Design&price=Rp%20300.000%20-%201.500.000&category=Desain" class="mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="flex-none w-64 sm:w-48 md:w-64 bg-white rounded-lg shadow-md">
                            <img src="https://assets-global.website-files.com/6410ebf8e483b5bb2c86eb27/6410ebf8e483b53d6186fc53_ABM%20College%20Web%20developer%20main.jpg" alt="Card 3" class="w-full h-40 rounded-t-lg object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">Jasa Pembuatan Website</h3>
                                <p class="text-gray-600 text-sm">untuk bisnis atau portofolio pribadi</p>
                                <a href="payment.php?service=Jasa%20Pembuatan%20Website&price=Rp%202.000.000%20-%2010.000.000&category=Teknologi" class="mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="flex-none w-64 sm:w-48 md:w-64 bg-white rounded-lg shadow-md">
                            <img src="https://www.megabaja.co.id/storage/2023/04/Kategori-Rumah-3-1600x900.jpg" alt="Card 4" class="w-full h-40 rounded-t-lg object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">Jasa Kebersihan</h3>
                                <p class="text-gray-600 text-sm">membersihkan rumah, kantor, DLL</p>
                                <a href="payment.php?service=Jasa%20Kebersihan&price=Rp%20200.000%20-%20500.000&category=Kebersihan" class="mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Pesan Sekarang
                                </a>
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
                                    Pesan Sekarang
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
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325489716988084235/image.png?ex=677bf9fe&is=677aa87e&hm=c180013f4d9f136f236e9f556c1b65f3670fc6095b8db4fb75ecb9766eab58e7&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Reja Kecap</h3>
                        <p class="testimonial-comment">"SiBantu benar-benar membantu saya dalam banyak hal. Aplikasinya simpel dan mudah digunakan, jadi saya bisa lebih fokus ke pekerjaan saya tanpa banyak gangguan."</p>
                    </div>
                </article>
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325489810927779951/image.png?ex=677bfa15&is=677aa895&hm=e57ffb9aefcc0a5a6678708eefa95b60a830d6b35c0a7e2923898e82ba2d69ea&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Rutz Gaming</h3>
                        <p class="testimonial-comment">"Gak nyangka banget ada aplikasi yang sepraktis ini. Saya jadi bisa lebih gampang atur waktu dan pekerjaan. Benar-benar ngebantu banget!"</p>
                    </div>
                </article>
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325489968507916369/image.png?ex=677bfa3a&is=677aa8ba&hm=2c8f958cc19602e5e009c1c3d2671f3f3589b1d548485e49fa91dcfb2a0e351f&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Yoyo bojongsantos</h3>
                        <p class="testimonial-comment">"Aplikasi ini bener-bener membantu banget, jadi gak perlu ribet lagi. Semua jadi lebih cepat dan praktis. Saya suka banget!"</p>
                    </div>
                </article>
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325491190853799956/image.png?ex=677bfb5e&is=677aa9de&hm=50678bb27f0939c993f4dcf529771f8de1f41f139adade0db1cc31528fe03cfe&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Natan Krepes</h3>
                        <p class="testimonial-comment">"Ni Sibantu Gacor Parah Bagus Banget coy bisa bantu bisa bikin krepes😍😍😍😍"</p>
                    </div>
                </article>
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325491517728755713/WhatsApp_Image_2025-01-05_at_22.49.23_a706d67b.jpg?ex=677bfbac&is=677aaa2c&hm=e5521bbfd9a5f64cbe7a95b8de12c2306c34a1c2da70c62e371975fa094b16f3&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Medek C6</h3>
                        <p class="testimonial-comment">"SiBantu benar-benar mengubah hidup saya! Dengan bantuan mereka, saya bisa menyelesaikan proyek dengan cepat dan efisien. Layanan yang sangat direkomendasikan! 🌟"</p>
                    </div>
                </article>
                <article class="testimonial-card">
                    <img loading="lazy" src="https://cdn.discordapp.com/attachments/1005856896751767573/1325492660890243192/WhatsApp_Image_2025-01-05_at_22.50.15_4eb75ed3.jpg?ex=677bfcbc&is=677aab3c&hm=a0c2a2b94fd06a1ee5ea30516832e2dbf7fad8f721d86c00a558d3070e205bb2&" class="testimonial-avatar" alt="User avatar" />
                    <div class="testimonial-text">
                        <h3 class="testimonial-name">Bin lapangan tembak</h3>
                        <p class="testimonial-comment">"Saya mau tidur"</p>
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

</body>
</html>