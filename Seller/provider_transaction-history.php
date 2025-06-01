<?php

session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: ../auth.php");
    exit;
}
include('../database/service_functions.php');
$user = getUserData($conn, $_SESSION['user_id']);
if (!$user) {
    header("Location: ../auth.php");
    exit;
}

$user_id = $_SESSION['user_id'];
// Get orders where the logged-in user is the seller
$sql = "SELECT * FROM orders WHERE seller_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    // You can still fetch the service name if needed
    $service = fetchServiceBySeller($conn, $row['service_id'], $row['seller_id']);
    $row['service'] = $service;
    $orders[] = $row;
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $customerInfo = getUserData($conn, $row['customer_id']); // Make sure this function exists
    $row['customer'] = $customerInfo;
    $service = fetchServiceBySeller($conn, $row['service_id'], $row['seller_id']);
    $row['service'] = $service;
    $orders[] = $row;
}

$totalPendapatan = 0;
foreach ($orders as $order) {
    if ($order['status'] !== 'declined') {
        $totalPendapatan += $order['service']['service_price'];
    }
}

?>



<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Total Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                        <a href="provider-dashboard.php" class="text-gray-500 hover:text-blue-600">Profil Penyedia Jasa</a>
                        <a href="provider_user-reviews.php" class="text-gray-500 hover:text-blue-600">User Review</a>
                    </div>

                    <!-- Transaction Content -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-2xl font-bold mb-4">Total Transaksi</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Jumlah Transaksi</h3>
                                    <?php $orders_count = count($orders); ?>
                                    <p class="text-3xl font-bold text-gray-800" id="total-transactions"><?= $orders_count ?></p>
                                </div>

                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Total Pendapatan</h3>
                                    <p class="text-3xl font-bold text-gray-800">
                                        Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Payment Status Tabs -->
                            <div class="mt-8">
                                <div class="border-b border-gray-200">
                                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="paymentTabs" role="tablist">
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 rounded-t-lg" id="Transaksi-tab" data-tab="Transaksi" type="button" role="tab" aria-controls="Transaksi" aria-selected="true">
                                                Transaksi
                                            </button>
                                        </li>
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="pending-tab" data-tab="pending" type="button" role="tab" aria-controls="pending" aria-selected="false">
                                                Menunggu Pembayaran
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Tab Content -->
                                <div id="tabContent" class="mt-4">
                                    <div id="Transaksi" class="tab-pane active">
                                        <?php foreach ($orders as $order):?>
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800"><?= htmlspecialchars($order['service']['service_name']) ?></h4>
                                                        <p class="text-sm text-gray-600">Customer: <?= htmlspecialchars($order['customer']['info_name']) ?></p>
                                                    </div>

                                                    <!-- Status Badge -->
                                                    <?php if ($order['status'] === 'completed'): ?>
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Lunas</span>
                                                    <?php elseif ($order['status'] === 'Work On Progress'): ?>
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Sedang di proses</span>
                                                    <?php elseif ($order['status'] === 'verified proof'): ?>
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Tindakan Seller</span>
                                                    <?php elseif ($order['status'] === 'declined'): ?>
                                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Pesanan Ditolak</span>
                                                    <?php endif; ?>
                                                    
                                                </div>

                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600"><?= htmlspecialchars($order['created_at']) ?></p>
                                                    <p class="font-semibold text-gray-800">Rp <?= number_format($order['service']['service_price'], 0, ',', '.') ?></p>
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="mt-3 flex space-x-2">
                                                    <?php if ($order['status'] === 'verified proof'): ?>
                                                        <form method="POST" >
                                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                            <input type="hidden" name="action" value="take">
                                                            <button 
                                                                type="button"
                                                                class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600" 
                                                                onclick="takeJob(<?= $order['id'] ?>)">
                                                                Take Job
                                                            </button>
                                                        </form>

                                                        <form method="POST">
                                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                            <input type="hidden" name="action" value="cancel">
                                                            <button 
                                                                type="button"
                                                                class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600" 
                                                                onclick="openConfirmationModal(<?= $order['id'] ?>)">
                                                                Decline Job
                                                            </button>
                                                        </form>

                                                        <?php elseif ($order['status'] === 'Work On Progress'): ?>
                                                            <div class="flex items-center space-x-2 mt-2">
                                                                <!-- Job Done Button -->
                                                                <form method="POST" >
                                                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                                    <input type="hidden" name="action" value="take">
                                                                    <button 
                                                                        type="button"
                                                                        class="flex items-center px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600" 
                                                                        onclick="JobDone(<?= $order['id'] ?>)">
                                                                        Job Done
                                                                    </button>
                                                                </form>

                                                                <!-- WhatsApp Button (wrapped in a form, but just acts as a link) -->
                                                                <form action="https://wa.me/<?= htmlspecialchars($order['customer']['info_phone']) ?>?text=Halo,%20saya%20ingin%20menghubungi%20Anda%20terkait%20order%20<?= urlencode($order['id']) ?>"
                                                                    method="GET" target="_blank" class="inline">
                                                                    <button type="submit" class="flex items-center px-3 py-1 bg-white text-green-600 text-sm rounded border border-green-600 hover:bg-green-50 transition">
                                                                        <i class="fab fa-whatsapp mr-2 text-green-600 text-base leading-none"></i>
                                                                        WhatsApp
                                                                    </button>
                                                                </form>
                                                            </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Pending Tab -->
                                    <div id="Transaksi" class="tab-pane hidden">
                                        <div class="space-y-4">
                                            <!-- Pending Transaction 1 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Perbaikan Listrik</h4>
                                                        <p class="text-sm text-gray-600">Rudi Hartono</p>
                                                    </div>
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Pembayaran</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">16 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 65.000</p>
                                                </div>
                                            </div>

                                            <!-- Pending Transaction 2 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Pembersihan Kantor</h4>
                                                        <p class="text-sm text-gray-600">Siti Aminah</p>
                                                    </div>
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Pembayaran</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">16 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 100.000</p>
                                                </div>
                                            </div>

                                            <!-- Pending Transaction 3 -->
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">Perbaikan Pipa</h4>
                                                        <p class="text-sm text-gray-600">Ahmad Fadillah</p>
                                                    </div>
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Pembayaran</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-gray-600">15 Maret 2024</p>
                                                    <p class="font-semibold text-gray-800">Rp 85.000</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" id="confirmationCancelModal">
                    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Konfirmasi</h2>
                        <p class="text-lg text-gray-600 mb-6">Apakah Anda yakin Cancel?</p>
                        <div class="flex justify-between space-x-4">
                            <button class="bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 focus:outline-none" onclick="closeConfirmationModal()">Batal</button>
                            <button class="bg-gray-800 text-white font-semibold py-3 px-6 rounded-lg hover:bg-gray-700 focus:outline-none" onclick="CancelJobConfirm()">Ya, Simpan</button>
                        </div>
                    </div>
                </div>

            </main>
        </div>

        <!-- Footer -->
        <?php require '../User/footer.php'; ?>
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

        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[data-tab]');
            const tabContents = document.querySelectorAll('.tab-pane');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('border-blue-600', 'text-blue-600');
                        t.classList.add('border-transparent');
                    });

                    // Add active class to clicked tab
                    tab.classList.remove('border-transparent');
                    tab.classList.add('border-blue-600', 'text-blue-600');

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show selected tab content
                    const tabId = tab.getAttribute('data-tab');
                    document.getElementById(tabId).classList.remove('hidden');
                });
            });

            // Set first tab as active by default
            tabs[0].click();
        });


        let orderIdToCancel = null;

        function openConfirmationModal(orderId) {
            orderIdToCancel = orderId;
            document.getElementById('confirmationCancelModal').classList.remove('hidden');
        }

        function closeConfirmationModal() {
            document.getElementById('confirmationCancelModal').classList.add('hidden');
            orderIdToCancel = null;
        }

        function CancelJobConfirm() {
            if (!orderIdToCancel) return;

            const data = new FormData();
            data.append('order_id', orderIdToCancel);
            data.append('action', 'CancelJobSeller');

            fetch('../database/payment.php', {
                method: 'POST',
                body: data,
            })
            .then(async response => {
                try {
                    const data = await response.json();
                    if (data.success) {
                        showNotification(data.message || 'Pesanan berhasil dibatalkan.', true);
                        setTimeout(() => { location.reload(); }, 500);
                    } else {
                        showNotification(data.message || 'Gagal membatalkan Pesanan.', false);
                    }
                } catch (e) {
                    showNotification('Gagal membatalkan Pesanan.', false);
                }
            })
            closeConfirmationModal();
        }

        function takeJob(orderId) {
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('action', 'takeJobSeller');

            fetch('../database/payment.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.success);
                if (data.success) {
                    showNotification(data.message || 'Pesanan berhasil Diambil.', true);
                    setTimeout(() => { location.reload(); }, 500);
                } else {
                    showNotification(data.message || 'Pesanan Gagal Diambil.', false);
                    alert(data.message || 'Pesanan Gagal Diambil.');
                }
            })
            .catch(error => {
                showNotification('Pesanan Gagal Diambil.', false);
                alert('Pesanan Gagal Diambil.');
                console.error('Error:', error);
            });
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
                console.log(data.success);
                if (data.success) {
                    showNotification(data.message || 'Pesanan berhasil Diselesaikan.', true);
                    setTimeout(() => { location.reload(); }, 500);
                } else {
                    showNotification(data.message || 'Pesanan Gagal Diselesaikan.', false);
                    alert(data.message || 'Pesanan Gagal Diselesaikan.');
                }
            })
            .catch(error => {
                showNotification('Pesanan Gagal Diselesaikan.', false);
                alert('Pesanan Gagal Diselesaikan.');
                console.error('Error:', error);
            });
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
