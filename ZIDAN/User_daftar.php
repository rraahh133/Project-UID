<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Alamat - SiBantu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-roboto">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-gray-700 shadow p-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">
                <a class="logo" href="./../index.php">
                    SiBantu
                </a>
            </h1>
            <div class="flex items-center space-x-4">
                <i class="fas fa-bell text-white"></i>
                <i class="fas fa-envelope text-white"></i>
                <img alt="User Profile" class="rounded-full" height="40"
                    src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg"
                    width="40" />
            </div>
        </header>
        <div class="flex flex-1 flex-col md:flex-row">
            <!-- Sidebar -->
            <div class="w-full md:w-1/5 bg-gray-200 p-4 shadow">
                <div class="flex items-center mb-4 bg-white p-4 rounded">
                    <img alt="User Profile" class="rounded-full mr-2" height="50"
                        src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg"
                        width="50" />
                    <div>
                        <h2 class="text-xl font-bold text-black">
                            Zaidan
                        </h2>
                        <p class="text-gray-600">
                            Member Silver
                        </p>
                    </div>
                </div>
                <!-- Dropdown Categories -->
                <div class="mb-4">
                    <div class="relative">
                        <button class="w-full text-left bg-white text-gray-700 px-4 py-2 rounded"
                            onclick="toggleDropdown('pembayaranDropdown')">
                            Transaksi
                            <i class="fas fa-chevron-down float-right mt-1"></i>
                        </button>
                        <div class="hidden mt-2 bg-white shadow rounded" id="pembayaranDropdown">
                            <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100" href="User_status.php">
                                Status Pembayaran
                            </a>
                            <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                href="User_Riwayat Transaksi.php">
                                Riwayat Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-4 rounded shadow mb-6">
                    <nav class="flex space-x-4">
                        <a class="text-gray-600" href="User_dashboard.php">
                            Biodata Diri
                        </a>
                        <a class="text-gray-800 font-bold" href="User_daftar.php">
                            Daftar Alamat
                        </a>
                    </nav>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">
                            Daftar Alamat
                        </h2>
                        <div>
                            <button
                                class="bg-green-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-green-600 transition duration-300"
                                onclick="openAddModal()">
                                Tambah Alamat
                            </button>
                        </div>
                    </div>
                    <div id="addressList">
                        <div class="bg-white p-6 rounded shadow mb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Alamat Lengkap</h2>
                                <div>
                                    <button
                                        class="bg-blue-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-600 transition duration-300 mr-2"
                                        onclick="editAddress(this)">Edit Alamat</button>
                                    <button
                                        class="bg-red-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-red-600 transition duration-300"
                                        onclick="confirmDeleteAddress(this)">Hapus Alamat</button>
                                </div>
                            </div>
                            <div class="address-container">
                                <p class="addressText">Jl. Merdeka No. 10, Jakarta</p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded shadow mb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Alamat Lengkap</h2>
                                <div>
                                    <button
                                        class="bg-blue-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-600 transition duration-300 mr-2"
                                        onclick="editAddress(this)">Edit Alamat</button>
                                    <button
                                        class="bg-red-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-red-600 transition duration-300"
                                        onclick="confirmDeleteAddress(this)">Hapus Alamat</button>
                                </div>
                            </div>
                            <div class="address-container">
                                <p class="addressText">Jl. Sudirman No. 20, Bandung</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden" id="editModal">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
            <h2 class="text-xl font-bold mb-4">
                Edit Alamat
            </h2>
            <input class="w-full p-2 border border-gray-300 rounded mb-4" id="editAddressInput"
                placeholder="Masukkan alamat baru" type="text" />
            <div class="flex justify-end">
                <button
                    class="bg-gray-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-600 transition duration-300 mr-2"
                    onclick="closeModal()">
                    Batal
                </button>
                <button
                    class="bg-blue-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-600 transition duration-300"
                    onclick="confirmEditAddress()">
                    Simpan
                </button>
            </div>
        </div>
    </div>
    <!-- Add Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden" id="addModal">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
            <h2 class="text-xl font-bold mb-4">
                Tambah Alamat
            </h2>
            <input class="w-full p-2 border border-gray-300 rounded mb-4" id="addAddressInput"
                placeholder="Masukkan alamat baru" type="text" />
            <div class="flex justify-end">
                <button
                    class="bg-gray-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-600 transition duration-300 mr-2"
                    onclick="closeAddModal()">
                    Batal
                </button>
                <button
                    class="bg-green-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-green-600 transition duration-300"
                    onclick="addAddress()">
                    Tambah
                </button>
            </div>
        </div>
    </div>
    <!-- Confirmation Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden" id="confirmModal">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
            <h2 class="text-xl font-bold mb-4">
                Konfirmasi
            </h2>
            <p class="mb-4">Apakah Anda yakin ingin mengedit alamat ini?</p>
            <div class="flex justify-end">
                <button
                    class="bg-gray-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-600 transition duration-300 mr-2"
                    onclick="closeConfirmModal()">
                    Batal
                </button>
                <button
                    class="bg-blue-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-600 transition duration-300"
                    onclick="saveAddress()">
                    Ya, Simpan
                </button>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden"
        id="deleteConfirmModal">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
            <h2 class="text-xl font-bold mb-4">
                Konfirmasi
            </h2>
            <p class="mb-4">Apakah Anda yakin ingin menghapus alamat ini?</p>
            <div class="flex justify-end">
                <button
                    class="bg-gray-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-600 transition duration-300 mr-2"
                    onclick="closeDeleteConfirmModal()">
                    Batal
                </button>
                <button
                    class="bg-red-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-red-600 transition duration-300"
                    onclick="deleteAddress()">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
    <!-- Notification -->
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg hidden" id="notification">
        Alamat berhasil diubah!
    </div>
    <!-- Footer Section -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="px-4 mx-auto max-w-screen-xl">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Logo and Description -->
                <div>
                    <h2 class="text-2xl font-bold">
                        SiBantu
                    </h2>
                    <p class="mt-4 text-sm">
                        Mitra andalan Anda untuk layanan sehari-hari. Hubungi kami kapan saja, di mana saja.
                    </p>
                </div>
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold">
                        Quick Links
                    </h3>
                    <nav class="mt-4 space-y-2">
                        <a class="block hover:underline" href="index.php">
                            Home
                        </a>
                        <a class="block hover:underline" href="faq.php">
                            FAQ
                        </a>
                    </nav>
                </div>
                <!-- Contact Us -->
                <div>
                    <h3 class="text-lg font-semibold">
                        Contact Us
                    </h3>
                    <nav class="mt-4 space-y-2">
                        <a class="block hover:underline" href="mailto:support@sibantu.com">
                            support@sibantu.com
                        </a>
                        <a class="block hover:underline" href="tel:+6281234567890">
                            +62 812 3456 7890
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </footer>
    <script>
        let currentEditElement = null;
        let currentDeleteElement = null;

        function editAddress(button) {
            currentEditElement = button.closest('.bg-white').querySelector('.addressText');
            const addressText = currentEditElement.textContent;
            document.getElementById('editAddressInput').value = addressText;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function confirmEditAddress() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function saveAddress() {
            const newAddress = document.getElementById('editAddressInput').value;
            if (newAddress) {
                currentEditElement.textContent = newAddress;
                closeConfirmModal();
                showNotification('Alamat berhasil diubah!');
            }
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        function addAddress() {
            const newAddress = document.getElementById('addAddressInput').value;
            if (newAddress) {
                const addressList = document.getElementById('addressList');
                const newAddressCard = `
                <div class="bg-white p-6 rounded shadow mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Alamat Lengkap</h2>
                        <div>
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-600 transition duration-300 mr-2" onclick="editAddress(this)">Edit Alamat</button>
                            <button class="bg-red-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-red-600 transition duration-300" onclick="confirmDeleteAddress(this)">Hapus Alamat</button>
                        </div>
                    </div>
                    <div class="address-container">
                        <p class="addressText">${newAddress}</p>
                    </div>
                </div>
            `;
                addressList.insertAdjacentHTML('beforeend', newAddressCard);
                closeAddModal();
                showNotification('Alamat berhasil ditambahkan!');
            }
        }

        function confirmDeleteAddress(button) {
            currentDeleteElement = button.closest('.bg-white');
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
        }

        function deleteAddress() {
            if (currentDeleteElement) {
                currentDeleteElement.remove();
                closeDeleteConfirmModal();
                showNotification('Alamat berhasil dihapus!');
            }
        }

        function closeDeleteConfirmModal() {
            document.getElementById('deleteConfirmModal').classList.add('hidden');
        }

        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.remove('hidden');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }

        function toggleDropdown(id) {
            var dropdown = document.getElementById(id);
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }
    </script>
</body>

</html>