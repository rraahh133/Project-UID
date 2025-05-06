<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Biodata Diri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100">
    <div class="flex flex-col h-screen">
        <!-- Header -->
        <header class="bg-white shadow p-4 flex justify-between items-center">
            <div class="text-2xl font-bold">
                SiBantu
            </div>
            <div class="flex items-center space-x-4">
                <i class="fas fa-bell text-gray-600"></i>
                <i class="fas fa-envelope text-gray-600"></i>
                <img alt="User Profile" class="w-10 h-10 rounded-full" height="40"
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
                <div class="bg-white p-4 rounded shadow">
                    <div class="flex space-x-4 border-b pb-2 mb-4">
                        <a class="text-gray-900 font-bold" href="index.php">Biodata Diri</a>
                        <a class="text-gray-600" href="User_daftar.php">Daftar Alamat</a>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        <div class="flex items-center space-x-4 mb-6">
                            <img alt="Profile Picture" class="w-24 h-24 rounded-full" height="100" width="100" />
                            <button class="bg-blue-500 text-white px-4 py-2 rounded">Pilih Foto</button>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-1/3 text-gray-600">Nama</div>
                                <div class="w-2/3 text-gray-900 font-bold" id="name-value">Zaidan Gustiawan Rabbani
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-1/3 text-gray-600">Tanggal Lahir</div>
                                <div class="w-2/3 text-gray-900 font-bold" id="dob-value">6 Agustus 2006</div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-1/3 text-gray-600">Jenis Kelamin</div>
                                <div class="w-2/3 text-gray-900 font-bold" id="gender-value">Pria</div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-1/3 text-gray-600">Email</div>
                                <div class="w-2/3 text-gray-900 font-bold" id="email-value">
                                    zaidangustiawanrabbani6826@gmail.com</div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-1/3 text-gray-600">Nomor HP</div>
                                <div class="w-2/3 text-gray-900 font-bold" id="phone-value">6285706280154</div>
                            </div>
                            <button class="bg-blue-500 text-white px-4 py-2 rounded" href="update_form.php">Edit
                                Biodata</button>
                        </div>
                    </div>
                </div>
            </main>
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