<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Status Pembayaran
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-roboto">
    <div class="flex flex-col h-screen">
        <!-- Header -->
        <header class="bg-gray-700 shadow p-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">
                <a class="logo" href="./../index.php">
                    SiBantu
                </a>
            </h1>
            <div class="flex items-center space-x-4">
                <i class="fas fa-bell text-white">
                </i>
                <i class="fas fa-envelope text-white">
                </i>
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
                            <i class="fas fa-chevron-down float-right mt-1">
                            </i>
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
                        <a class="text-gray-600" href="User_daftar.php">
                            Daftar Alamat
                        </a>
                    </nav>
                </div>
                <div class="bg-white p-6 rounded-md shadow-md">
                    <h2 class="text-xl font-bold mb-4">
                        Status Pembayaran
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr>
                                    <th class="border-b-2 p-2">
                                        No
                                    </th>
                                    <th class="border-b-2 p-2">
                                        Nomor Transaksi
                                    </th>
                                    <th class="border-b-2 p-2">
                                        Tanggal
                                    </th>
                                    <th class="border-b-2 p-2">
                                        Status
                                    </th>
                                    <th class="border-b-2 p-2">
                                        Detail
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-b p-2">
                                        1
                                    </td>
                                    <td class="border-b p-2">
                                        TRX123456
                                    </td>
                                    <td class="border-b p-2">
                                        01 Desember 2024
                                    </td>
                                    <td class="border-b p-2 text-green-500">
                                        Lunas
                                    </td>
                                    <td class="border-b p-2 text-blue-500 cursor-pointer"
                                        onclick="openModal('TRX123456', 'Lunas')">
                                        Lihat
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-b p-2">
                                        2
                                    </td>
                                    <td class="border-b p-2">
                                        TRX654321
                                    </td>
                                    <td class="border-b p-2">
                                        29 November 2024
                                    </td>
                                    <td class="border-b p-2 text-red-500">
                                        Belum Lunas
                                    </td>
                                    <td class="border-b p-2 text-blue-500 cursor-pointer"
                                        onclick="openModal('TRX654321', 'Belum Lunas')">
                                        Lihat
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Footer Section -->
    
    <!-- Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden" id="modal">
        <div class="bg-white p-6 rounded-md shadow-md w-11/12 md:w-1/3">
            <h2 class="text-xl font-bold mb-4">
                Detail Pembayaran
            </h2>
            <div id="transaction-details">
                <table class="w-full text-left">
                    <tbody>
                        <tr>
                            <td class="border-b p-2 font-bold">
                                Nomor Transaksi:
                            </td>
                            <td class="border-b p-2" id="transaction-id">
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b p-2 font-bold">
                                Jenis Jasa:
                            </td>
                            <td class="border-b p-2" id="service-type">
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b p-2 font-bold">
                                Tanggal:
                            </td>
                            <td class="border-b p-2" id="transaction-date">
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b p-2 font-bold">
                                Status:
                            </td>
                            <td class="border-b p-2" id="transaction-status">
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b p-2 font-bold">
                                Jumlah:
                            </td>
                            <td class="border-b p-2" id="transaction-amount">
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b p-2 font-bold">
                                Metode Pembayaran:
                            </td>
                            <td class="border-b p-2" id="payment-method">
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b p-2 font-bold">
                                Nama Penerima:
                            </td>
                            <td class="border-b p-2" id="recipient-name">
                            </td>
                        </tr>
                        <tr class="hidden" id="note-row">
                            <td class="border-b p-2 font-bold text-red-500">
                                Catatan:
                            </td>
                            <td class="border-b p-2 text-red-500" id="transaction-note">
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2" colspan="2">
                                <img alt="Image of Membersihkan Rumah" class="w-3/4 h-auto rounded-md mx-auto"
                                    height="400" id="service-image"
                                    src="https://storage.googleapis.com/a1aa/image/rdmu026VRJKOEZ0OAB3FfkROMOtdqqVGWGXjal8VO2YNmJBKA.jpg"
                                    width="600" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button class="mt-4 bg-blue-500 text-white p-2 rounded-md" onclick="closeModal()">
                Close
            </button>
        </div>
    </div>
    <script>
        function openModal(transactionId, status) {
            document.getElementById('modal').classList.remove('hidden');
            let details = {
                'TRX123456': {
                    'serviceType': 'Membersihkan Rumah',
                    'date': '01 Desember 2024',
                    'status': 'Lunas',
                    'amount': 'Rp 100.000',
                    'paymentMethod': 'Transfer Bank',
                    'recipientName': 'SiBersih',
                    'note': '',
                    'image': 'https://storage.googleapis.com/a1aa/image/rdmu026VRJKOEZ0OAB3FfkROMOtdqqVGWGXjal8VO2YNmJBKA.jpg'
                },
                'TRX654321': {
                    'serviceType': 'Pindahan Rumah',
                    'date': '29 November 2024',
                    'status': 'Belum Lunas',
                    'amount': 'Rp 500.000',
                    'paymentMethod': 'Transfer Bank',
                    'recipientName': 'Pindahan satset',
                    'note': 'Segera lakukan pembayaran sebelum 10 Desember 2024',
                    'image': 'https://storage.googleapis.com/a1aa/image/4qUQB6qR8kxq4W9V3jJ2R7XqZx1UHtV6L4Qj8MlX6V5T9N8O7R6P5.jpg'
                }
            };

            let transaction = details[transactionId];
            document.getElementById('transaction-id').innerText = transactionId;
            document.getElementById('service-type').innerText = transaction.serviceType;
            document.getElementById('transaction-date').innerText = transaction.date;
            document.getElementById('transaction-status').innerText = transaction.status;
            document.getElementById('transaction-amount').innerText = transaction.amount;
            document.getElementById('payment-method').innerText = transaction.paymentMethod;
            document.getElementById('recipient-name').innerText = transaction.recipientName;
            document.getElementById('service-image').src = transaction.image;
            document.getElementById('service-image').alt = `Image of ${transaction.serviceType}`;

            if (transaction.note) {
                document.getElementById('note-row').classList.remove('hidden');
                document.getElementById('transaction-note').innerText = transaction.note;
            } else {
                document.getElementById('note-row').classList.add('hidden');
            }
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
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