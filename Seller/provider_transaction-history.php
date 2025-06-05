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
    if ($order['status'] !== 'declined' && $order['status'] !== 'pending proof' && $order['status'] !== 'Work On Progress' && $order['status'] !== 'komplain') {
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
                                                    <?php elseif ($order['status'] === 'pending proof'): ?>
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Pembayaran Di Verifikasi</span>
                                                    <?php elseif ($order['status'] === 'verified proof'): ?>
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Tindakan Seller</span>
                                                    <?php elseif ($order['status'] === 'declined'): ?>
                                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Pesanan Ditolak</span>
                                                    <?php elseif ($order['status'] === 'komplain'): ?>
                                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Pesanan Dikomplain</span>
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

                                                        <?php elseif ($order['status'] === 'Work On Progress' ): ?>
                                                            <div class="flex items-center space-x-2 mt-2">
                                                                <!-- Job Done Button -->
                                                                <form method="POST" >
                                                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                                    <input type="hidden" name="action" value="take">
                                                                    <button
                                                                        type="button"
                                                                        class="flex items-center px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600"
                                                                        onclick="openModal(
                                                                        <?= $order['id'] ?>, 
                                                                        '<?= $order['customer']['info_name'] ?>', 
                                                                        '<?= $order['customer']['info_email'] ?>',
                                                                        '<?= $order['customer']['info_phone'] ?>',
                                                                        '<?= $order['id'] ?>',
                                                                        '<?= $order['service']['service_name'] ?>',
                                                                        '<?= $order['service']['service_price'] ?>',
                                                                        '<?= $order['service']['status'] ?>')">
                                                                        Details
                                                                    </button>
                                                                </form>

                                                                <!-- WhatsApp Button (wrapped in a form, but just acts as a link) -->
                                                               <form action="https://wa.me/<?= htmlspecialchars($order['customer']['info_phone']) ?>"
                                                                    method="GET" target="_blank" class="inline">
                                                                    <input type="hidden" name="text" value="<?= htmlspecialchars('Halo, saya ingin menghubungi Anda terkait order ' . $order['id']) ?>">
                                                                    <button type="submit"
                                                                            class="flex items-center px-3 py-1 bg-white text-green-600 text-sm rounded border border-green-600 hover:bg-green-50 transition">
                                                                        <i class="fab fa-whatsapp mr-2 text-green-600 text-base leading-none"></i>
                                                                        WhatsApp
                                                                    </button>
                                                                </form>
                                                            </div>

                                                        <?php elseif ($order['status'] === 'komplain' ): ?>
                                                                <!-- WhatsApp Button -->
                                                                <form action="https://wa.me/<?= htmlspecialchars($order['customer']['info_phone']) ?>"
                                                                    method="GET" target="_blank" class="inline">
                                                                    <input type="hidden" name="text" value="<?= htmlspecialchars('Halo, saya ingin mendiskusikan lebih lanjut mengenai komplain pada pesanan #' . $order['id'] . ' untuk layanan ' . $order['service']['service_name'] . '.') ?>">
                                                                    <button type="submit"
                                                                            class="flex items-center px-3 py-1 bg-white text-green-600 text-sm rounded border border-green-600 hover:bg-green-50 transition">
                                                                        <i class="fab fa-whatsapp mr-2 text-green-600 text-base leading-none"></i>
                                                                        Chat User
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Modal Cancel -->
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

                <!-- Modal Detail Customer -->
                <div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden justify-center items-center z-50">
                    <div class="bg-white rounded-xl p-6 w-96 shadow-xl relative">
                        <h2 class="text-xl font-bold text-blue-700 mb-4">Detail Pelanggan</h2>

                        <!-- Customer Details -->
                        <div id="customer-details" class="mb-4 space-y-2">
                            <p><strong>Nama:</strong> <span id="customer-name" class="text-gray-800"></span></p>
                            <p><strong>Email:</strong> <span id="customer-email" class="text-gray-800"></span></p>
                            <p><strong>Nomor HP:</strong> <span id="customer-phone" class="text-gray-800"></span></p>
                            <p><strong>Order ID:</strong> <span id="order-id" class="text-gray-800"></span></p>
                            <p><strong>Service:</strong> <span id="service-name" class="text-gray-800"></span></p>
                            <p><strong>Harga:</strong> <span id="service-price" class="text-gray-800"></span></p>
                        </div>

                        <!-- Upload Form -->
                        <form method="POST" id="jobDoneForm" class="space-y-4">
                            <input type="hidden" name="order_id" id="order_id">
                            <input type="hidden" name="action" value="take">
                            <input type="hidden" name="image_base64" id="image_base64">

                            <div>
                                <label for="modal_upload" class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar:</label>
                                <input
                                    type="file"
                                    id="modal_upload"
                                    name="payment_proof"
                                    accept=".png, .jpg, .jpeg"
                                    required
                                    onchange="convertImageToBase64(this)"
                                    class="block w-full text-gray-700 border border-gray-300 rounded-lg cursor-pointer
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100"
                                />
                                <p id="file-name" class="text-sm text-gray-500 italic mt-1"></p>
                            </div>

                            <div class="flex justify-end space-x-3 pt-3">
                                <button
                                    type="button"
                                    onclick="closeModal()"
                                    class="bg-gray-300 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-400 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    Selesai
                                </button>
                            </div>

                        </form>
                    </div>
                </div>


            </main>
        </div>
        <!-- Footer -->
        <?php require '../User/footer.php'; ?>
    </div>

    <script>
        function openModal(orderId, customerName, customerEmail, customerPhone, orderIdText, serviceName, servicePrice, orderStatus) {
            document.getElementById('order_id').value = orderId;

            document.getElementById('customer-name').textContent = customerName || 'N/A';
            document.getElementById('customer-email').textContent = customerEmail || 'N/A';
            document.getElementById('customer-phone').textContent = customerPhone || 'N/A';
            // orderIdText is passed separately, but you can just use orderId if they're the same
            document.getElementById('order-id').textContent = orderIdText || orderId || 'N/A';
            document.getElementById('service-name').textContent = serviceName || 'N/A';
            document.getElementById('service-price').textContent = servicePrice ? `Rp ${servicePrice}` : 'Rp 0';

            const modal = document.getElementById('modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }


        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('modal').classList.remove('flex');
            document.getElementById('image_base64').value = '';
        }
            
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

        function convertImageToBase64(input) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const base64String = e.target.result;
                document.getElementById('image_base64').value = base64String;
                document.getElementById('file-name').textContent = file.name;
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        document.getElementById('jobDoneForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const orderId = document.getElementById('order_id').value;
            JobDone(orderId);
        });


        function JobDone(orderId) {
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('action', 'JobDone');

            // Get the base64 image value from hidden input
            const base64Image = document.getElementById('image_base64').value;
            formData.append('image_base64', base64Image);

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
