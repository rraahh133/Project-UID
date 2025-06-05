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
$orders = array_filter($orders, function($order) {
    return strtolower($order['status']) !== 'completed';
});

while ($row = mysqli_fetch_assoc($result)) {
    $service = fetchServiceBySeller($conn, $row['service_id'], $row['seller_id']);
    $row['service'] = $service;

    if (strtolower($row['status']) !== 'completed') {
        $orders[] = $row;
    }
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
                                                <?= 'TRX' . htmlspecialchars($order['id']) ?>
                                            </td>

                                            <td class="py-3 px-4 text-sm text-gray-700"><?= date("d F Y", strtotime($order['created_at'])) ?></td>
                                            <?php
                                                $badgeColor = match (strtolower($order['status'])) {
                                                    'pending proof', 'Work On Progress' => 'text-yellow-800',
                                                    'completed', 'verified proof', 'lunas' => 'text-green-800',
                                                    'declined', 'komplain' => 'text-red-800',
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
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-lg relative animate-fade-in">
            <!-- Close Button (Top Right) -->
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl transition-colors duration-200" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Detail Pembayaran</h2>
            <div id="transaction-details" class="mb-4">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-600 w-1/3">Nomor Transaksi:</td>
                            <td class="py-3 px-4 text-sm text-gray-800 font-mono" id="transaction-id"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-600">Jenis Jasa:</td>
                            <td class="py-3 px-4 text-sm text-gray-800" id="service-type"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-600">Tanggal:</td>
                            <td class="py-3 px-4 text-sm text-gray-800" id="transaction-date"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-600">Status:</td>
                            <td class="py-3 px-4 text-sm text-gray-800" id="transaction-status"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-600">Total Transaksi:</td>
                            <td class="py-3 px-4 text-sm text-blue-700 font-semibold" id="transaction-amount"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-600">Metode Pembayaran:</td>
                            <td class="py-3 px-4 text-sm text-gray-800" id="payment-method"></td>
                        </tr>
                        <tr id="note-row" class="hidden">
                            <td class="py-3 px-4 text-sm font-semibold text-gray-600">Catatan:</td>
                            <td class="py-3 px-4 text-sm text-gray-800" id="transaction-note"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4" colspan="2">
                                <img alt="Seller proof image not available" class="w-3/4 h-auto rounded-lg mx-auto shadow-md" id="service-image" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <button 
                    class="bg-white text-green-600 border border-green-600 px-5 py-2 rounded-lg font-semibold hover:bg-green-50 transition-colors duration-200 shadow-sm flex items-center gap-2"
                    id="komplain-btn"
                    onclick="JobDone('<?= htmlspecialchars($order['id']) ?>')">
                    <i class="fas fa-exclamation-circle"></i> Selesaikan Pesanan
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
            if (transaction.status === 'komplain') {
                document.getElementById('komplain-btn').style.display = 'inline-flex';
            } else {
                document.getElementById('komplain-btn').style.display = 'none';
            }
            if (transaction.note) {
                document.getElementById('note-row').classList.remove('hidden');
                document.getElementById('transaction-note').innerText = transaction.note;
            } else {
                document.getElementById('note-row').classList.add('hidden');
            }
        }

        function JobDone(orderId) {
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('action', 'JobDone');

            fetch('../database/payment.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Pesanan berhasil Diselesaikan.', true);
                    setTimeout(() => { location.reload(); }, 500);
                } else {
                    showNotification(data.message || 'Pesanan gagal Diselesaikan.', false);
                    alert(data.message || 'Pesanan gagal Diselesaikan.');
                }
            })
            .catch(error => {
                showNotification('Pesanan gagal Diselesaikan.', false);
                alert('Pesanan gagal Diselesaikan.');
                console.error('Error:', error);
            });
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

        function showNotification(message, isSuccess) {
            const notification = document.createElement('div');
            notification.textContent = message;
            notification.className = `fixed top-4 right-4 text-white py-3 px-6 rounded-lg shadow-lg ${
                isSuccess ? 'bg-green-600' : 'bg-red-600'
            }`;
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</body>

</html>