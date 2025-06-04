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
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Profil Penyedia Jasa</title>
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
                        <a href="provider-dashboard.php" class="text-blue-600 font-semibold">Profil Penyedia Jasa</a>
                        <a href="provider_user-reviews.php" class="text-gray-500 hover:text-blue-600">User Review </a>
                    </div>

                    <!-- Profile Header -->
                    <div class="flex items-center gap-8 mb-8">
                        <img src="<?= $user['seller_info_profile_picture'] ? 'data:image/jpeg;base64,' . $user['seller_info_profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" class="w-36 h-36 rounded-full border shadow">
                        <div>
                            <h2 class="text-2xl font-bold"><?= htmlspecialchars($user['seller_info_name'] ?? 'User') ?></h2>
                            <p class="text-gray-600"><?= htmlspecialchars($user['user_email'] ?? '-') ?></p>
                        </div>
                    </div>

                    <!-- Profile Details -->
                    <div class="space-y-4 text-gray-700">
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Nama</span>
                            <span><?= htmlspecialchars($user['seller_info_name'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Tanggal Lahir</span>
                            <span><?= htmlspecialchars($user['seller_info_birthdate'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Jenis Kelamin</span>
                            <span><?= htmlspecialchars($user['seller_info_gender'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Nomor HP</span>
                            <span><?= htmlspecialchars($user['seller_info_phone'] ?? '-') ?></span>
                        </div>
                    </div>

                    <!-- Edit Button -->
                    <div class="text-center mt-8">
                        <a href="provider-form.php" class="inline-block bg-gray-800 text-white px-6 py-3 rounded-full shadow hover:bg-gray-700 transition">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <?php require '../User/footer.php'; ?>



    <script>
        const modal = document.getElementById('confirmation-modal');
        const notification = document.getElementById('notification');
        const errorNotification = document.getElementById('error-notification');
        const saveButton = document.getElementById('save-button');
        const confirmButton = document.getElementById('confirm-button');
        const cancelButton = document.getElementById('cancel-button');
        const menuButton = document.getElementById('menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        saveButton.addEventListener('click', () => {
            const name = document.getElementById('provider-name').value;
            const email = document.getElementById('provider-email').value;
            const phone = document.getElementById('provider-phone').value;
            const address = document.getElementById('provider-address').value;
            const description = document.getElementById('provider-description').value;

            if (name && email && phone && address && description) {
                modal.classList.remove('hidden');
            } else {
                errorNotification.classList.remove('hidden');
                setTimeout(() => {
                    errorNotification.classList.add('hidden');
                }, 3000);
            }
        });

        confirmButton.addEventListener('click', () => {
            modal.classList.add('hidden');
            notification.classList.remove('hidden');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        });

        cancelButton.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });


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
