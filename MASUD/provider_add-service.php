<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tambah Layanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-gray-800 shadow-md p-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">
                <a href="./../index.php">SiBantu</a>
            </h1>
            <div class="flex items-center gap-4">
                <i class="fas fa-bell text-white text-lg"></i>
                <i class="fas fa-envelope text-white text-lg"></i>
                <!-- Assuming user profile image -->
                <img src="profile_pic_url" class="rounded-full w-10 h-10 border-2 border-white" />
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="bg-white p-8 rounded-xl shadow-md">
                <h2 class="text-2xl font-bold mb-6">Tambah Layanan</h2>

                <?php if (isset($error_message)): ?>
                    <div class="text-red-500 mb-4"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>
                <?php if (isset($success_message)): ?>
                    <div class="text-green-500 mb-4"><?= htmlspecialchars($success_message) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label for="service_name" class="block text-gray-700">Nama Layanan</label>
                        <input type="text" name="service_name" id="service_name" class="w-full p-3 border rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="service_description" class="block text-gray-700">Deskripsi Layanan</label>
                        <textarea name="service_description" id="service_description" class="w-full p-3 border rounded-md" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="service_price" class="block text-gray-700">Harga Layanan</label>
                        <input type="number" name="service_price" id="service_price" class="w-full p-3 border rounded-md" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-full shadow hover:bg-blue-500 transition">
                            Tambah Layanan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
