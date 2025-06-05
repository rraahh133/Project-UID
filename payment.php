<?php
require './database/service_functions.php';
$user = getUserData($conn);
if (!$user) {
    header("Location: ./auth.php");
    exit;
}
$service_id = $_POST['service_id'] ?? null;
$seller_user_id = $_POST['user_id'] ?? null;
$seller = getUserData($conn, $seller_user_id);
$service = fetchServiceBySeller($conn, $service_id, $seller_user_id);
$dashboardLink = './User/user_dashboard.php';
if (($user['usertype'] ?? '') === 'seller') {
    $dashboardLink = './Seller/provider-dashboard.php';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - SiBantu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <?php include './header.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Detail Pembayaran</h1>

            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Detail Layanan -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Detail Layanan</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-gray-600">Penyedia Jasa</p>
                                <p class="font-semibold"><?= htmlspecialchars($seller['info_name']) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Email Penyedia Jasa</p>
                                <p class="font-semibold"><?= htmlspecialchars($seller['info_email']) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Layanan</p>
                                <p class="font-semibold"><?= htmlspecialchars($service['service_name']) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Kategori</p>
                                <p class="font-semibold"><?= htmlspecialchars($service['service_type']) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Total Pembayaran</p>
                                <p class="text-2xl font-bold text-blue-600"><?= htmlspecialchars($service['service_price']) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Hubungi Penjual</p>
                                <a href="https://wa.me/<?= htmlspecialchars($service['provider_number'])?>?text=Halo,%20saya%20ingin%20menanyakan%20stok%20untuk%20layanan%20<?=urlencode($service['service_name'])?>" 
                                    target="_blank"
                                    class="inline-flex items-center justify-center w-10 h-10 mt-4 bg-white text-blue-600 rounded-lg shadow border border-blue-600 hover:bg-blue-50 transition">
                                        <i class="fab fa-whatsapp text-xl"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pembayaran -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Informasi User</h2>
                        <form id="orderForm" method="POST" action="order.php" enctype="multipart/form-data" class="space-y-4">
                            <!-- Hidden Inputs -->
                            <input type="hidden" name="service_id" value="<?= htmlspecialchars($service_id) ?>">
                            <input type="hidden" name="seller_id" value="<?= htmlspecialchars($seller_user_id) ?>">

                            <div>
                                <label class="block text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="fullname" required
                                    value="<?= htmlspecialchars($user['info_name'] ?? '') ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" required
                                    value="<?= htmlspecialchars($user['info_email'] ?? '') ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-1">Nomor Telepon</label>
                                <input type="tel" name="phone" required
                                    value="<?= htmlspecialchars($user['info_phone'] ?? '') ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-1">Pilih Alamat</label>
                                <select id="addressPicker" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 mb-2">
                                    <option value="">-- Pilih alamat --</option>
                                    <?php 
                                    $firstAddress = $user['addresses'][0] ?? null;
                                    foreach ($user['addresses'] ?? [] as $addr): 
                                        $isSelected = ($firstAddress && $addr['id'] === $firstAddress['id']) ? 'selected' : '';
                                    ?>
                                        <option 
                                            value="<?= htmlspecialchars($addr['alamat_lengkap']) ?>"
                                            data-nama="<?= htmlspecialchars($addr['nama_penerima']) ?>"
                                            data-phone="<?= htmlspecialchars($addr['nomor_telepon']) ?>"
                                            data-keterangan="<?= htmlspecialchars($addr['keterangan']) ?>"
                                            <?= $isSelected ?>
                                        >
                                            <?= htmlspecialchars($addr['nama_penerima']) ?> - <?= htmlspecialchars(substr($addr['alamat_lengkap'], 0, 50)) ?>...
                                        </option>
                                    <?php endforeach; ?>
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
    <script>
        const input = document.getElementById('payment_proof');
        const fileNameDisplay = document.getElementById('file-name');

        input.addEventListener('change', () => {
            if (input.files.length > 0) {
                fileNameDisplay.textContent = `Selected file: ${input.files[0].name}`;
            } else {
                fileNameDisplay.textContent = '';
            }
        });

        const addressPicker = document.getElementById('addressPicker');
        const addressTextarea = document.getElementById('addressTextarea');

        addressPicker.addEventListener('change', () => {
            addressTextarea.value = addressPicker.value;
        });

        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }
        });
    </script>
</body>
</html> 