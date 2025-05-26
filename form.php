<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Kustom - SiBantu</title>
<link rel="stylesheet" href="./assets/css/index.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
<script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
</head>
<body class="flex flex-col min-h-screen">

    <!-- Header Section -->
    <header>
        <div class="navbar">
            <a class="logo" href="index.php">SiBantu</a>
            <div class="nav-center">
                <a href="index.php#explore-section">Layanan</a>
                <a href="index.php#explore-section">Tentang Kami</a>
            </div>
            <div class="nav-kanan">
                <a href="login_page.php">Masuk</a>
            </div>
            <!-- Ikon Hamburger untuk Mobile -->
            <div class="hamburger" onclick="toggleMenu()">
                &#9776;
            </div>
        </div>
        <!-- Menu Mobile -->
        <div id="mobile-menu" class="mobile-menu hidden">
            <a href="index.php#testimonial-section">Testimoni</a>
            <a href="index.php#explore-section">Katalog</a>
            <a href="login_page.php">Masuk</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <section class="form-section px-4 mx-auto max-w-screen-xl py-24">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-center text-gray-900 md:text-5xl lg:text-6xl">Form Pesanan Kustom</h1>
            <p class="mb-8 text-lg font-normal text-center text-gray-600 lg:text-xl sm:px-16 lg:px-48">Kirimkan permintaan kustom Anda, dan kami akan segera menghubungi Anda.</p>

            <form id="customForm" action="#" method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto" onsubmit="handleFormSubmit(event)">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Anda</label>
                    <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Anda</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Masukkan email Anda" required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Masukkan nomor telepon Anda" required>
                </div>
                <div class="mb-4">
                    <label for="details" class="block text-sm font-medium text-gray-700">Detail Pesanan Anda</label>
                    <textarea id="details" name="details" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Berikan detail tentang permintaan kustom Anda" required></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-700 text-white py-2 px-4 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">Kirimkan Pesanan</button>
            </form>
        </section>
    </main>

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

    <!-- Notification and Modal -->
    <div id="notification" class="fixed top-4 right-4 bg-green-500 text-white py-4 px-6 rounded-lg shadow-lg hidden text-lg flex items-center space-x-2">
        <span>🔔</span>
        <span>Pesanan berhasil dikirim!</span>
    </div>

    
    <div id="confirmationModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
            <h2 class="text-lg font-bold mb-4">Lanjutkan kirim pesanan ini?</h2>
            <div class="flex justify-between mt-4">
                <button onclick="confirmOrder()" class="bg-blue-700 text-white py-2 px-4 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">Ya, Lanjutkan</button>
                <button onclick="closeModal()" class="bg-gray-700 text-white py-2 px-4 rounded-lg hover:bg-gray-800 focus:ring-4 focus:ring-gray-300">Kembali</button>
            </div>
        </div>
    </div>

    <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
            <h2 class="text-lg font-bold mb-4">Pesanan terkirim!</h2>
            <p>Pesanan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.</p>
            <button onclick="closeSuccessModal()" class="mt-4 w-full bg-blue-700 text-white py-2 px-4 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">Tutup</button>
        </div>
    </div>

<script>
    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }

    function showNotification() {
    const notification = document.getElementById('notification');
    notification.classList.remove('hidden');
    setTimeout(() => {
        notification.classList.add('hidden');
    }, 3000); // Notifikasi akan hilang setelah 3 detik
}

    function handleFormSubmit(event) {
        event.preventDefault();
        const confirmationModal = document.getElementById('confirmationModal');
        confirmationModal.classList.remove('hidden');
    }

    function closeModal() {
        const confirmationModal = document.getElementById('confirmationModal');
        confirmationModal.classList.add('hidden');
    }

    function confirmOrder() {
            closeModal();
            showNotification();
            const successModal = document.getElementById('successModal');
            successModal.classList.remove('hidden');
    }


    function closeSuccessModal() {
        const successModal = document.getElementById('successModal');
        successModal.classList.add('hidden');
    }
</script>
</body>
</html>
