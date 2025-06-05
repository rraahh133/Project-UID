<?php
require './database/service_functions.php';
$user = getUserData($conn);
if (!$user) {
    header("Location: ../auth.php");
    exit;
}

// Proses kirim email jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $to = "sibantu@sigmaku.biz.id";
    $subject = "Pesanan Kustom dari " . htmlspecialchars($_POST['name']);

    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $details = htmlspecialchars($_POST['details']);

    $message = "Nama: $name\n";
    $message .= "Email: $email\n";
    $message .= "Nomor Telepon: $phone\n";
    $message .= "Detail Pesanan:\n$details\n";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('notification').classList.remove('hidden');
            document.getElementById('successModal').classList.remove('hidden');
        });
        </script>";
    } else {
        echo "<script>alert('Gagal mengirim email. Silakan coba lagi.');</script>";
    }
}
?>
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

    <!-- Header -->
    <?php require './header.php'; ?>

    <!-- Main -->
    <main class="flex-grow">
        <section class="form-section px-4 mx-auto max-w-screen-xl py-24">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight text-center text-gray-900">Form Pesanan Kustom</h1>
            <p class="mb-8 text-lg text-center text-gray-600">Kirimkan permintaan kustom Anda, dan kami akan segera menghubungi Anda.</p>

            <form id="customForm" action="" method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto" onsubmit="handleFormSubmit(event)">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Anda</label>
                    <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Anda</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Masukkan email Anda" required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Masukkan nomor telepon Anda" required>
                </div>
                <div class="mb-4">
                    <label for="details" class="block text-sm font-medium text-gray-700">Detail Pesanan Anda</label>
                    <textarea id="details" name="details" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Berikan detail tentang permintaan kustom Anda" required></textarea>
                </div>
                <button type="submit" name="submit" class="w-full bg-blue-700 text-white py-2 px-4 rounded-lg hover:bg-blue-800">Kirimkan Pesanan</button>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="px-4 mx-auto max-w-screen-xl">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div>
                    <h2 class="text-2xl font-bold">SiBantu</h2>
                    <p class="mt-4 text-sm">Mitra andalan Anda untuk layanan sehari-hari. Hubungi kami kapan saja, di mana saja.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Tautan Cepat</h3>
                    <nav class="mt-4 space-y-2">
                        <a href="index.php" class="block hover:underline">Beranda</a>
                        <a href="faq.php" class="block hover:underline">FAQ</a>
                    </nav>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Hubungi Kami</h3>
                    <nav class="mt-4 space-y-2">
                        <a href="mailto:sibantu@sigmaku.biz.id" class="block hover:underline">sibantu@sigmaku.biz.id</a>
                        <a href="tel:+6285706280154" class="block hover:underline">+62 857-0628-0154</a>
                    </nav>
                </div>
            </div>
        </div>
    </footer>

    <!-- Notifikasi -->
    <div id="notification" class="fixed top-4 right-4 bg-green-500 text-white py-4 px-6 rounded-lg shadow-lg hidden text-lg flex items-center space-x-2">
        <span>🔔</span>
        <span>Pesanan berhasil dikirim!</span>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="confirmationModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
            <h2 class="text-lg font-bold mb-4">Lanjutkan kirim pesanan ini?</h2>
            <div class="flex justify-between mt-4">
                <button onclick="confirmOrder()" class="bg-blue-700 text-white py-2 px-4 rounded-lg">Ya, Lanjutkan</button>
                <button onclick="closeModal()" class="bg-gray-700 text-white py-2 px-4 rounded-lg">Kembali</button>
            </div>
        </div>
    </div>

    <!-- Modal Sukses -->
    <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
            <h2 class="text-lg font-bold mb-4">Pesanan terkirim!</h2>
            <p>Pesanan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.</p>
            <button onclick="closeSuccessModal()" class="mt-4 w-full bg-blue-700 text-white py-2 px-4 rounded-lg">Tutup</button>
        </div>
    </div>

<script>
    let isConfirmed = false;

    function handleFormSubmit(event) {
        if (!isConfirmed) {
            event.preventDefault();
            document.getElementById('confirmationModal').classList.remove('hidden');
        }
    }

    function confirmOrder() {
        isConfirmed = true;
        document.getElementById('confirmationModal').classList.add('hidden');
        document.getElementById('customForm').submit();
    }

    function closeModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
    }

    function closeSuccessModal() {
        document.getElementById('successModal').classList.add('hidden');
    }
</script>

</body>
</html>
