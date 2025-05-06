<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Daftar Alamat - SiBantu
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
</head>

<body class="bg-gray-100">
    <div class="flex flex-col h-screen">
        <!-- Header -->
        <header class="bg-white shadow p-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">
                <a class="logo" href="index.php">
                    SiBantu
                </a>
            </h1>
            <div class="flex items-center space-x-4">
                <i class="fas fa-bell text-gray-700"></i>
                <i class="fas fa-envelope text-gray-700"></i>
                <img alt="User Profile" class="rounded-full" height="40"
                    src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg"
                    width="40" />
            </div>
        </header>
        <div class="flex flex-1">
            <!-- Sidebar -->
            <div class="w-1/4 bg-white p-4 shadow">
                <div class="flex items-center mb-4">
                    <img alt="User Profile" class="rounded-full mr-2" height="50"
                        src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg"
                        width="50">
                    <div>
                        <h2 class="text-xl font-bold">Zaidan</h2>
                        <p class="text-gray-600">Member Silver</p>
                    </div>
                </div>
                <!-- Dropdown Categories -->
                <div class="mb-4">
                    <div class="relative">
                        <button class="w-full text-left bg-gray-200 text-gray-700 px-4 py-2 rounded"
                            onclick="toggleDropdown('pembayaranDropdown')">
                            Pembayaran
                            <i class="fas fa-chevron-down float-right mt-1"></i>
                        </button>
                        <div id="pembayaranDropdown" class="hidden mt-2 bg-white shadow rounded">
                            <a href="User_status.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Status
                                Pembayaran</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Riwayat Pembayaran</a>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="relative">
                        <button class="w-full text-left bg-gray-200 text-gray-700 px-4 py-2 rounded"
                            onclick="toggleDropdown('transaksiDropdown')">
                            Transaksi
                            <i class="fas fa-chevron-down float-right mt-1"></i>
                        </button>
                        <div id="transaksiDropdown" class="hidden mt-2 bg-white shadow rounded">
                            <a href="User_Riwayat Transaksi.php"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Riwayat Transaksi</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Detail Transaksi</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-4 rounded shadow mb-6">
                    <nav class="flex space-x-4">
                        <a class="text-gray-600" href="User_dashboard.php">Biodata Diri</a>
                        <a class="text-gray-800 font-bold" href="User_daftar.php">Daftar Alamat</a>
                    </nav>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">
                            Daftar Alamat
                        </h2>
                        <div>
                            <button class="bg-blue-500 text-white px-4 py-2 rounded mr-2" onclick="addAddress()">
                                Tambah Alamat
                            </button>
                            <button class="bg-red-500 text-white px-4 py-2 rounded" onclick="deleteAddress()">
                                Hapus Alamat
                            </button>
                        </div>
                    </div>
                    <div class="address-list-container">
                        <ul class="address-list list-disc pl-5">
                            <li>
                                Jl. Merdeka No. 10, Jakarta
                            </li>
                            <li>
                                Jl. Diponegoro No. 23, Bandung
                            </li>
                            <li>
                                Jl. Gatot Subroto No. 5, Surabaya
                            </li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        function addAddress() {
            const address = prompt("Masukkan alamat baru:");
            if (address) {
                const addressList = document.querySelector('.address-list');
                const newAddress = document.createElement('li');
                newAddress.textContent = address;
                addressList.appendChild(newAddress);
            }
        }

        function deleteAddress() {
            const addressList = document.querySelector('.address-list');
            addressList.removeChild(addressList.lastChild);
        }
    </script>
    <script>
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