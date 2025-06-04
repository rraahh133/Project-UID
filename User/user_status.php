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

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $service = fetchServiceBySeller($conn, $row['service_id'], $row['seller_id']);
    $row['service'] = $service;
    $orders[] = $row;
}
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Status Pembayaran</title>
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

                    <h2 class="text-2xl font-bold mb-6">Status Pembayaran</h2>

                    <div class="overflow-x-auto">

                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">No</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Nomor Transaksi</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Tanggal</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Status</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if (count($orders) > 0): ?>
                                    <?php foreach ($orders as $index => $order): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4 text-sm text-gray-700"><?= $index + 1 ?></td>
                                            <td class="py-3 px-4 text-sm text-gray-700">
                                                <?= 'TRX' . htmlspecialchars($order['id']) . '0' . htmlspecialchars($order['customer_id']) ?>
                                            </td>

                                            <td class="py-3 px-4 text-sm text-gray-700"><?= date("d F Y", strtotime($order['created_at'])) ?></td>
                                            <?php
                                                $badgeColor = match (strtolower($order['status'])) {
                                                    'pending proof', 'Work On Progress' => 'text-yellow-800',
                                                    'completed', 'verified proof', 'lunas' => 'text-green-800',
                                                    'declined' => 'text-red-800',
                                                    default => 'text-gray-800'
                                                };
                                            ?>  
                                            <td class="py-3 px-4 text-sm <?= $badgeColor ?>">
                                                <?= htmlspecialchars($order['status']) ?>
                                            </td>

                                            <td class="py-3 px-4 text-sm text-blue-500 cursor-pointer hover:text-blue-700"
                                                onclick="openModal('<?= $order['id'] ?>')">
                                                Lihat
                                            </td>
                                        </tr>
                                        
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="py-3 px-4 text-sm text-gray-500 text-center">Belum ada pesanan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
        <!-- Footer -->
        <?php require 'footer.php'; ?>
    </div>

    <!-- Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden" id="modal">
        <div class="bg-white p-8 rounded-xl shadow-md w-11/12 md:w-1/3">
            <h2 class="text-2xl font-bold mb-6">Detail Pembayaran</h2>
            <div id="transaction-details">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Nomor Transaksi:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="transaction-id"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Jenis Jasa:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="service-type"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Tanggal:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="transaction-date"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Status:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="transaction-status"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Jumlah:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="transaction-amount"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-700">Metode Pembayaran:</td>
                            <td class="py-3 px-4 text-sm text-gray-700" id="payment-method"></td>
                        </tr>
                        <tr class="hidden" id="note-row">
                            <td class="py-3 px-4 text-sm font-semibold text-red-500">Catatan:</td>
                            <td class="py-3 px-4 text-sm text-red-500" id="transaction-note"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4" colspan="2">
                                <img alt="Seller proof image not available" class="w-3/4 h-auto rounded-lg mx-auto shadow-md" id="service-image" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-6 text-center">
                <button class="bg-gray-800 text-white px-6 py-3 rounded-full shadow hover:bg-gray-700 transition" onclick="closeModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openModal(transactionId) {
            document.getElementById('modal').classList.remove('hidden');
            <?php
                $orders_js = [];
                foreach ($orders as $order) {
                    $orders_js[$order['id']] = [
                        'transaction_number' => 'TRX' . htmlspecialchars($order['id']) . '0' . htmlspecialchars($order['customer_id']),
                        'serviceType' => htmlspecialchars($order['service']['service_type'] ?? ''),
                        'date' => date("d F Y", strtotime($order['created_at'])),
                        'status' => htmlspecialchars($order['status']),
                        'amount' => 'Rp ' . number_format($order['service']['service_price'] ?? 0, 0, ',', '.'),
                        'paymentmethod' => htmlspecialchars($order['payment_method'] ?? 'BANK'),
                        'recipientName' => htmlspecialchars($order['recipient_name'] ?? ''),
                        'note' => htmlspecialchars($order['note'] ?? ''),
                        'image' => htmlspecialchars($order['seller_proof'])
                    ];
                }
                echo "const ordersData = " . json_encode($orders_js) . ";";
            ?>
            let transaction = ordersData[transactionId];
            console.log(transaction);
            if (!transaction) {
                alert('Transaksi tidak ditemukan.');
                return;
            }
            document.getElementById('transaction-id').innerText = transactionId;
            document.getElementById('service-type').innerText = transaction.serviceType;
            document.getElementById('transaction-date').innerText = transaction.date;
            document.getElementById('transaction-status').innerText = transaction.status;
            document.getElementById('transaction-amount').innerText = transaction.amount;
            document.getElementById('payment-method').innerText = transaction.paymentmethod;
            document.getElementById('service-image').src = transaction.image;
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