<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <div class="flex justify-between items-center bg-white p-4 shadow">
            <div class="text-2xl font-bold text-gray-900">
                <a class="logo" href="index.php">SiBantu</a>
            </div>
            <div class="flex items-center space-x-4">
                <i class="fas fa-bell text-gray-700"></i>
                <i class="fas fa-envelope text-gray-700"></i>
                <img alt="User Profile" class="rounded-full" height="40"
                    src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg"
                    width="40" />
            </div>
        </div>
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
            <div class="w-3/4 p-4">
                <div class="bg-white p-4 rounded shadow mb-4">
                    <div class="border-b border-gray-200 mb-4">
                        <ul class="flex space-x-4">
                            <li><a class="text-gray-700 font-semibold" href="User_dashboard.php">Biodata Diri</a></li>
                            <li><a class="text-gray-700" href="User_daftar.php">Daftar Alamat</a></li>
                        </ul>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded shadow">
                            <h3 class="text-lg font-bold mb-2">Jumlah Transaksi</h3>
                            <div class="flex items-center">
                                <i class="fas fa-users text-2xl text-gray-700 mr-4"></i>
                                <div>
                                    <div class="text-2xl font-bold">894</div>
                                    <div class="text-gray-600">Jasa</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded shadow">
                            <h3 class="text-lg font-bold mb-2">Jumlah Pengeluaran</h3>
                            <div class="flex items-center">
                                <i class="fas fa-arrow-up text-2xl text-gray-700 mr-4"></i>
                                <div class="text-2xl font-bold">Rp984.200,00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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