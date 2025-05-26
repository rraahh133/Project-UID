<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - SiBantu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="index.php" class="text-2xl font-bold text-blue-600">SiBantu</a>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-gray-600 hover:text-blue-600">Home</a>
                    <a href="catalog.php" class="text-gray-600 hover:text-blue-600">Katalog</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Detail Pembayaran</h1>
            
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Detail Layanan -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Detail Layanan</h2>
                        <?php
                        if (isset($_GET['service'])) {
                            $service = $_GET['service'];
                            $price = $_GET['price'] ?? '';
                            $category = $_GET['category'] ?? '';
                        } else {
                            $service = "Custom Request";
                            $price = $_GET['price'] ?? '';
                            $category = "Custom";
                        }
                        ?>
                        <div class="space-y-4">
                            <div>
                                <p class="text-gray-600">Layanan</p>
                                <p class="font-semibold"><?= htmlspecialchars($service) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Kategori</p>
                                <p class="font-semibold"><?= htmlspecialchars($category) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Total Pembayaran</p>
                                <p class="text-2xl font-bold text-blue-600"><?= htmlspecialchars($price) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pembayaran -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Informasi Pembayaran</h2>
                        <form action="process_payment.php" method="POST" class="space-y-4">
                            <input type="hidden" name="service" value="<?= htmlspecialchars($service) ?>">
                            <input type="hidden" name="price" value="<?= htmlspecialchars($price) ?>">
                            
                            <div>
                                <label class="block text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="fullname" required
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="tel" name="phone" required
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-2">Alamat</label>
                                <textarea name="address" required rows="3"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-2">Metode Pembayaran</label>
                                <select name="payment_method" required
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="">Pilih metode pembayaran</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="ewallet">E-Wallet</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                                Bayar Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="bg-blue-50 rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4">Informasi Penting</h3>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-info-circle mt-1 mr-2 text-blue-600"></i>
                        <span>Pembayaran akan diverifikasi dalam waktu 1x24 jam</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-info-circle mt-1 mr-2 text-blue-600"></i>
                        <span>Detail pembayaran akan dikirimkan melalui email</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-info-circle mt-1 mr-2 text-blue-600"></i>
                        <span>Untuk bantuan, silakan hubungi customer service kami</span>
                    </li>
                </ul>
            </div>
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
</body>
</html> 