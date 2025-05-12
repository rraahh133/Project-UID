<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Jasa - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .main-content {
            padding: 20px;
        }

        .service-card {
            transition: transform 0.2s;
        }

        .service-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <h3 class="mb-4">Admin Panel</h3>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="admin.php" class="nav-link">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="admin_users.php" class="nav-link">
                            <i class="fas fa-users me-2"></i> Manajemen Pengguna
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="admin_products.php" class="nav-link active">
                            <i class="fas fa-tools me-2"></i> Manajemen Jasa
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="admin_transactions.php" class="nav-link">
                            <i class="fas fa-shopping-cart me-2"></i> Transaksi
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="admin_reports.php" class="nav-link">
                            <i class="fas fa-chart-bar me-2"></i> Laporan
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="logout.php" class="nav-link text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manajemen Jasa</h2>
                </div>

                <!-- Filter Tabs -->
                <ul class="nav nav-tabs mb-4" id="serviceTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending"
                            type="button" role="tab">
                            Pengajuan Baru
                            <span class="badge bg-warning ms-2">3</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active"
                            type="button" role="tab">
                            Jasa Aktif
                            <span class="badge bg-success ms-2">3</span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="serviceTabsContent">
                    <!-- Pending Services -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        <div class="row">
                            <?php
                            $services = [
                                [
                                    'id' => 1,
                                    'title' => 'Jasa Desain Logo',
                                    'description' => 'Desain logo profesional untuk bisnis Anda dengan 3 revisi gratis.',
                                    'provider' => 'John Designer',
                                    'price' => 'Rp 500.000',
                                ],
                                [
                                    'id' => 2,
                                    'title' => 'Jasa Pembuatan Website',
                                    'description' => 'Pembuatan website responsif dengan fitur modern dan SEO friendly.',
                                    'provider' => 'Sarah Developer',
                                    'price' => 'Rp 2.500.000',
                                ],
                                [
                                    'id' => 3,
                                    'title' => 'Jasa Video Editing',
                                    'description' => 'Editing video profesional untuk konten YouTube atau iklan.',
                                    'provider' => 'Mike Editor',
                                    'price' => 'Rp 750.000',
                                ],
                                // Tambahkan lebih banyak data di sini
                            ];
                            ?>

                            <div class="row">
                                <?php foreach ($services as $service): ?>
                                <div class="col-md-4 mb-4">
                                    <div
                                        class="card service-card bg-white shadow-lg rounded-lg overflow-hidden transform transition-all hover:scale-105 hover:shadow-xl h-full">
                                        <div class="card-body p-6 flex flex-col justify-between h-full">
                                            <div>
                                                <div class="flex justify-between items-start mb-4">
                                                    <h5 class="text-xl font-semibold text-gray-800">
                                                        <?= htmlspecialchars($service['title']) ?></h5>
                                                    <span
                                                        class="badge bg-yellow-500 text-white px-3 py-1 rounded-full text-sm">Menunggu
                                                        Persetujuan</span>
                                                </div>
                                                <p class="text-gray-600 mb-4">
                                                    <?= htmlspecialchars($service['description']) ?></p>
                                                <p class="text-gray-700">
                                                    <strong>Penyedia:</strong>
                                                    <?= htmlspecialchars($service['provider']) ?><br>
                                                    <strong>Harga:</strong> <?= htmlspecialchars($service['price']) ?>
                                                </p>
                                            </div>
                                            <div class="flex justify-between items-center mt-6">
                                                <div class="flex space-x-2">
                                                    <a href="?approve=<?= $service['id'] ?>"
                                                        class="bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300 text-sm">
                                                        <i class="fas fa-check"></i> Setujui
                                                    </a>
                                                    <a href="?reject=<?= $service['id'] ?>"
                                                        class="bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300 text-sm">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    // Sample array of active services
                    $activeServices = [
                        [
                            'id' => 1,
                            'title' => 'Jasa Fotografi',
                            'description' => 'Fotografi profesional untuk acara pernikahan dan bisnis',
                            'provider' => 'Lisa Photographer',
                            'price' => 'Rp 1.500.000',
                        ],
                        [
                            'id' => 2,
                            'title' => 'Jasa Copywriting',
                            'description' => 'Penulisan konten profesional untuk website dan media sosial',
                            'provider' => 'David Writer',
                            'price' => 'Rp 300.000',
                        ],
                        [
                            'id' => 3,
                            'title' => 'Jasa Social Media Management',
                            'description' => 'Manajemen dan optimasi akun media sosial bisnis Anda',
                            'provider' => 'Emma Manager',
                            'price' => 'Rp 2.000.000',
                        ],
                    ];
                    ?>




                    <!-- Active Services -->
                    <div class="tab-pane fade p-4 bg-gray-100 min-h-screen" id="active" role="tabpanel">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Active Services</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($activeServices as $service): ?>
                            <div
                                class="bg-white rounded-xl shadow-md hover:shadow-lg transition transform hover:scale-[1.02] h-full flex flex-col">
                                <div class="p-6 flex flex-col justify-between h-full">
                                    <div class="flex justify-between items-start mb-4">
                                        <h5 class="text-xl font-bold text-gray-900">
                                            <?= htmlspecialchars($service['title']) ?></h5>
                                        <span
                                            class="bg-green-500 text-white text-xs px-3 py-1 rounded-full">Aktif</span>
                                    </div>
                                    <p class="text-gray-700 text-sm mb-4">
                                        <?= htmlspecialchars($service['description']) ?></p>
                                    <p class="text-sm text-gray-600 mb-4">
                                        <strong>Penyedia:</strong> <?= htmlspecialchars($service['provider']) ?><br>
                                        <strong>Harga:</strong> <?= htmlspecialchars($service['price']) ?>
                                    </p>
                                    <div class="mt-auto flex justify-end">
                                        <button onclick="deleteService(<?= $service['id'] ?>)"
                                            class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded transition duration-300">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        function deleteService(id) {
            if (confirm('Apakah Anda yakin ingin menghapus jasa ini?')) {
                window.location.href = '?delete=' + id;
            }
        }
    </script>
</body>

</html>