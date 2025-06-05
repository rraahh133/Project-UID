<?php
require './database/service_functions.php';
$user = getUserData($conn);
if (!$user) {
    header("Location: ./auth.php");
    exit;
}
$service_id = $_POST['service_id'] ?? null;
$seller_id = $_POST['seller_id'] ?? null;
$fullname = $_POST['fullname'] ?? null;
$email = $_POST['email'] ?? null;
$phone = $_POST['phone'] ?? null;
$address = $_POST['address'] ?? null;

if (isset($_FILES['payment_proof'])) {
    $file = $_FILES['payment_proof'];
}
$service = fetchServiceBySeller($conn, $service_id, $seller_id);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pembayaran - SiBantu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <header class="bg-white shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="max-w-4xl mx-auto px-6 py-4 flex justify-center">
            <a href="index.php" class="text-xl md:text-2xl font-bold text-black no-underline">
                SiBantu
            </a>
        </div>
    </header>

    <div class="bg-white shadow-md rounded-xl p-6 max-w-md w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Detail Pembayaran</h1>

        <!-- Payment Details -->
        <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h2 class="text-lg font-semibold text-blue-700 mb-3">Transfer ke Rekening</h2>
            <p class="text-gray-700 mb-1">Bank: <strong><?= htmlspecialchars($service['provider_bank']) ?></strong></p>
            <p class="text-gray-700 mb-1">Nomor Rekening: <strong><?= htmlspecialchars($service['provider_account_number']) ?></strong></p>
            <p class="text-gray-700 mb-1">Atas Nama: <strong><?= htmlspecialchars($service['provider_name']) ?></strong></p>
            <p class="text-gray-700">Jumlah Transfer: <strong><?= htmlspecialchars($service['service_price']) ?></strong></p>
        </div>

        <!-- Upload Payment Proof -->
        <form id="orderForm" method="POST" enctype="multipart/form-data" class="space-y-4" novalidate>
            <!-- Add hidden inputs for existing data -->
            <input type="hidden" name="service_id" value="<?= htmlspecialchars($service_id) ?>" />
            <input type="hidden" name="seller_id" value="<?= htmlspecialchars($seller_id) ?>" />
            <input type="hidden" name="fullname" value="<?= htmlspecialchars($fullname) ?>" />
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>" />
            <input type="hidden" name="phone" value="<?= htmlspecialchars($phone) ?>" />
            <input type="hidden" name="address" value="<?= htmlspecialchars($address) ?>" />
            <label class="block text-gray-700 font-semibold mb-2" for="payment_proof">
                Bukti Pembayaran (Upload File)
            </label>

            <input
                type="file"
                id="payment_proof"
                name="payment_proof"
                accept=".png, .jpg, .jpeg, .pdf"
                required
                class="block w-full text-gray-700 border border-gray-300 rounded-lg cursor-pointer
                       file:mr-4 file:py-2 file:px-4
                       file:rounded file:border-0
                       file:text-sm file:font-semibold
                       file:bg-blue-50 file:text-blue-700
                       hover:file:bg-blue-100"
            />

            <p id="file-name" class="text-sm text-gray-500 italic"></p>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition"
            >
                Bayar Sekarang
            </button>
        </form>
    </div>

<script>

function createOrder(form) {
    const orderForm = form; // use passed form
    const paymentProofInput = document.getElementById('payment_proof');
    const fileNameDisplay = document.getElementById('file-name');

    // Show selected file name
    paymentProofInput.addEventListener('change', function (e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : '';
        fileNameDisplay.textContent = fileName;
    });

    // Prepare FormData and send AJAX
    const formData = new FormData(orderForm);
    formData.append('action', 'Buyer Creates an Order');

    fetch('./database/payment.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Marks as AJAX
        }
    })
    .then(res => res.json())
    .then(data => {
        const msg = document.createElement('div');
        msg.className = 'mt-4 p-3 rounded text-center';
        msg.textContent = data.message;
        msg.style.backgroundColor = data.success ? '#d1fae5' : '#fee2e2';
        msg.style.color = data.success ? '#065f46' : '#991b1b';

        orderForm.appendChild(msg);

        if (data.success) {
            orderForm.reset();
            fileNameDisplay.textContent = '';

            setTimeout(() => {
                window.location.href = './User/user_status.php';
            }, 3000); // Redirect after 3 seconds
        }
    })
    .catch(err => {
        console.error(err);
        alert('Photo Tidak Boleh Lebih Dari 1MB.');
    });
}

document.getElementById('orderForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!this.checkValidity()) {
        this.reportValidity();
        return;
    }

    createOrder(this);
});
</script>

</body>
</html>
