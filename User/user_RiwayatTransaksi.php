<?php
include('../database/service_functions.php'); // where getUserData() is defined
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$user = getUserData($conn, $user_id);
if (!$user) {
    header("Location: ../auth.php");
    exit;
}
?>


<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Riwayat Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <?php require '../header.php'; ?>


        <div class="flex flex-1 flex-col md:flex-row">
            <!-- Sidebar -->
            <?php require './sidebar.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <!-- Tabs -->
                    <div class="flex gap-6 border-b pb-4 mb-6">
                        <a href="user_dashboard.php" class="text-gray-500 hover:text-blue-600">Biodata Diri</a>
                        <a href="user_daftar.php" class="text-gray-500 hover:text-blue-600">Daftar Alamat</a>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-2xl font-bold mb-6">Riwayat Transaksi</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">No</th>
                                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">ID Transaksi</th>
                                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Jenis Jasa</th>
                                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Tanggal</th>
                                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Harga</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 text-sm text-gray-700">1</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">TRX001</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">Jasa Membersihkan Rumah</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">2024-10-01</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">Rp100.000,00</td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td class="py-3 px-4 text-right font-semibold text-gray-700" colspan="4">Total Transaksi</td>
                                        <td class="py-3 px-4 font-semibold text-gray-700">Rp100.000,00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <?php require 'footer.php'; ?>

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