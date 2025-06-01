<aside class="w-full md:w-64 bg-white p-6 shadow-md">
    <div class="flex items-center gap-3 mb-6">
        <img src="<?= $user['seller_info_profile_picture'] ? 'data:image/jpeg;base64,' . $user['seller_info_profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" class="rounded-full w-12 h-12">
        <div>
            <h2 class="text-lg font-semibold"><?= htmlspecialchars($user['seller_info_name'] ?? 'User') ?></h2>
            <p class="text-gray-500 text-sm"><?= htmlspecialchars($user['user_email'] ?? 'User') ?></p>
        </div>
    </div>

    <!-- Dropdown -->
    <div>
        <button onclick="toggleDropdown('transaksiDropdown')" class="w-full bg-gray-100 px-4 py-2 rounded flex justify-between items-center text-gray-700 font-medium hover:bg-gray-200">
            Transaksi
            <i class="fas fa-chevron-down ml-2"></i>
        </button>
        <div id="transaksiDropdown" class="hidden mt-2">
            <a href="provider_add-service.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Service</a>
            <a href="provider_transaction-history.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Riwayat Transaksi</a>
        </div>
    </div>
</aside>