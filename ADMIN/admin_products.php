<?php
// Include DB connection
require '../database/db_connect.php'; // Adjust path as needed

// $conn is assumed to be your mysqli connection

$services = [];
$statusCounts = [
    'approved' => 0,
    'pending' => 0,
    'rejected' => 0
];

try {
    // Get services with provider name
    $sql = "
        SELECT s.service_id, s.service_name, s.service_description, s.service_price, s.status, s.service_image, s.service_type, i.name AS provider_name
        FROM seller_service s
        JOIN seller_information i ON s.user_id = i.user_id
        ORDER BY s.service_id DESC
    ";

    if ($result = $conn->query($sql)) {
        $services = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
    } else {
        throw new Exception("Error fetching services: " . $conn->error);
    }

    // Get counts grouped by status
    $countSql = "SELECT status, COUNT(*) AS count FROM seller_service GROUP BY status";
    $countResult = $conn->query($countSql);
    if (!$countResult) {
        throw new Exception("Error fetching status counts: " . $conn->error);
    }
    while ($row = $countResult->fetch_assoc()) {
        $status = $row['status'];
        $count = (int)$row['count'];

        if (array_key_exists($status, $statusCounts)) {
            $statusCounts[$status] = $count;
        }
    }
    $countResult->free();


} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
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
                            <span class="badge bg-warning ms-2"><?= $statusCounts['pending'] ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active"
                            type="button" role="tab">
                            Jasa Aktif
                            <span class="badge bg-success ms-2"><?= $statusCounts['approved'] ?></span>
                        </button>
                    </li>
                </ul>


                <!-- Tab Content -->
                <div class="tab-content" id="serviceTabsContent">

                    <!-- Pending Services -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        <div class="row">
                            <?php foreach ($services as $service): ?>
                            <?php if ($service['status'] === 'pending'): ?>
                            <div class="col-md-4 mb-4">
                                <div
                                    class="card service-card bg-white shadow-lg rounded-lg overflow-hidden transform transition-all hover:scale-105 hover:shadow-xl h-full">
                                    <div class="card-body p-6 flex flex-col justify-between h-full">
                                        <div>
                                            <div
                                                class="relative w-full pb-[75%] mb-4 bg-gray-100 rounded-md overflow-hidden">
                                                <img src="<?= htmlspecialchars($service['service_image']) ?>"
                                                    alt="Service Image"
                                                    class="absolute top-0 left-0 w-full h-full object-cover" />
                                            </div>

                                            <div class="flex justify-between items-start mb-4">
                                                <h5 class="text-xl font-semibold text-gray-800">
                                                    <?= htmlspecialchars($service['service_name']) ?>
                                                </h5>

                                                <div class="flex space-x-2">
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                        <?= htmlspecialchars($service['service_type']) ?>
                                                    </span>
                                                    <span
                                                        class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-500 text-white">
                                                        Status: <?= ucfirst($service['status']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="text-gray-600 mb-4">
                                                <?= htmlspecialchars($service['service_description']) ?>
                                            </p>
                                            <p class="text-gray-700">
                                                <strong>Penyedia:</strong>
                                                <?= htmlspecialchars($service['provider_name']) ?><br>
                                                <strong>Harga:</strong> Rp
                                                <?= number_format($service['service_price'], 0, ',', '.') ?>
                                            </p>
                                        </div>
                                        <div class="flex justify-between items-center mt-6">
                                            <div class="flex space-x-2">
                                                <button
                                                    onclick="updateServiceStatus(<?= $service['service_id'] ?>, 'approved')"
                                                    class="bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300 text-sm">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                                <button
                                                    onclick="updateServiceStatus(<?= $service['service_id'] ?>, 'rejected')"
                                                    class="bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300 text-sm">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Active Services -->
                    <div class="tab-pane fade" id="active" role="tabpanel">
                        <div class="row">
                            <?php foreach ($services as $service): ?>
                            <?php if ($service['status'] === 'approved'): ?>
                            <div class="col-md-4 mb-4">
                                <div
                                    class="card service-card bg-white shadow-lg rounded-lg overflow-hidden transform transition-all hover:scale-105 hover:shadow-xl h-full">
                                    <div class="card-body p-6 flex flex-col justify-between h-full">
                                        <div>
                                            <div
                                                class="relative w-full pb-[75%] mb-4 bg-gray-100 rounded-md overflow-hidden">
                                                <img src="<?= htmlspecialchars($service['service_image']) ?>"
                                                    alt="Service Image"
                                                    class="absolute top-0 left-0 w-full h-full object-cover" />
                                            </div>

                                            <div class="flex justify-between items-start mb-4">
                                                <h5 class="text-xl font-semibold text-gray-800">
                                                    <?= htmlspecialchars($service['service_name']) ?>
                                                </h5>

                                                <div class="flex space-x-2">
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                        <?= htmlspecialchars($service['service_type']) ?>
                                                    </span>
                                                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                                        Aktif
                                                    </span>
                                                </div>
                                            </div>

                                            <p class="text-gray-600 mb-4">
                                                <?= htmlspecialchars($service['service_description']) ?>
                                            </p>

                                            <p class="text-gray-700">
                                                <strong>Penyedia:</strong>
                                                <?= htmlspecialchars($service['provider_name']) ?><br>
                                                <strong>Harga:</strong> Rp
                                                <?= number_format($service['service_price'], 0, ',', '.') ?>
                                            </p>

                                        </div>
                                        <div class="flex justify-end mt-6">
                                            <button onclick="deleteService(<?= $service['service_id'] ?>)"
                                                class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded transition duration-300">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
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
        function deleteService(serviceId) {
            if (!confirm('Apakah Anda yakin ingin menghapus jasa ini?')) return;

            fetch('../database/admin-service.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'deleteService',
                        service_id: serviceId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(err => {
                    alert('Something went wrong!');
                    console.error(err);
                });
        }

        function updateServiceStatus(serviceId, status) {
            fetch('../database/admin-service.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'updateServiceStatus',
                        service_id: serviceId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        // Reload or update the UI
                        location.reload();
                    }
                });
        }
    </script>
</body>

</html>