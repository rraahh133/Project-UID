<?php
require '../database/service_functions.php';
$user = getUserData($conn);
if (!$user) {
    header("Location: ../auth.php");
    exit;
}
?>


<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Biodata Diri
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
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
                        <a href="user_dashboard.php" class="text-blue-600 font-semibold">Biodata Diri</a>
                        <a href="user_daftar.php" class="text-gray-500 hover:text-blue-600">Daftar Alamat</a>
                    </div>

                    <!-- Profile Header -->
                    <div class="flex items-center gap-8 mb-8">
                        <img src="<?= $user['info_profile_picture'] ? 'data:image/jpeg;base64,' . $user['info_profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" class="w-36 h-36 rounded-full border shadow">
                        <div>
                            <h2 class="text-2xl font-bold"><?= htmlspecialchars($user['name'] ?? 'User') ?></h2>
                            <p class="text-gray-600"><?= htmlspecialchars($user['user_email'] ?? '-') ?></p>
                        </div>
                    </div>

                    <!-- Biodata Fields -->
                    <div class="space-y-4 text-gray-700">
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Nama</span>
                            <span><?= htmlspecialchars($user['info_name'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Tanggal Lahir</span>
                            <span><?= htmlspecialchars($user['info_birthdate'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Jenis Kelamin</span>
                            <span><?= htmlspecialchars($user['info_gender'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium">Nomor HP</span>
                            <span><?= htmlspecialchars($user['info_phone'] ?? '-') ?></span>
                        </div>
                    </div>

                    <!-- Edit Button -->
                    <div class="text-center mt-8">
                        <a href="update_form.php" class="inline-block bg-gray-800 text-white px-6 py-3 rounded-full shadow hover:bg-gray-700 transition">
                            Edit Biodata
                        </a>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <?php require 'footer.php'; ?>

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