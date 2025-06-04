<?php
include "../database/service_functions.php"; // Include your MySQLi connection
date_default_timezone_set('Asia/Jakarta'); // Set timezone to Asia/Jakarta
$user_id = $_SESSION["user_id"];

$detect = getUserData($conn, $user_id);
$dashboardLink = './User/user_dashboard.php';
if (($user['usertype'] ?? '') === 'admin') {
    $dashboardLink = './admin.php';
}

try {
    $sql = "
        SELECT users.*, user_information.*
        FROM users
        INNER JOIN user_information ON users.user_id = user_information.user_id
        WHERE users.user_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $total_users = 0;
    $result_total = $conn->query("SELECT COUNT(*) AS total_users FROM users");
    if ($result_total) {
        $row = $result_total->fetch_assoc();
        $total_users = (int)$row['total_users'];
        $result_total->free();
    }
    $total_services = 0;
    $result_services = $conn->query("SELECT COUNT(*) AS total_services FROM seller_service");
    if ($result_services) {
        $row = $result_services->fetch_assoc();
        $total_services = (int)$row['total_services'];
        $result_services->free();
    }

    $result_orders = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
    $row = $result_orders->fetch_assoc();
    $totalOrders = $row['total_orders'];

    // Get the last 7 days of orders ( Grafik Statistik 1)
    $days = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $days[$date] = 0;
    }

    $startDate = array_key_first($days);
    $endDate = array_key_last($days);

    // Fetch orders count per day within last 7 days
    $sql = "
        SELECT DATE(created_at) as order_date, COUNT(*) as total
        FROM orders
        WHERE created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
        GROUP BY order_date
    ";
    $query = $conn->query($sql);

    while ($row = $query->fetch_assoc()) {
        if (isset($days[$row['order_date']])) {
            $days[$row['order_date']] = (int)$row['total'];
        }
    }

    // Compute cumulative counts
    $runningTotal = 0;
    $cumulativeCounts = [];
    foreach ($days as $date => $count) {
        $runningTotal += $count;
        $cumulativeCounts[] = $runningTotal;
    }

    // Prepare labels as "d M" format
    $labels = array_map(fn($d) => date('d M', strtotime($d)), array_keys($days));
    $counts = array_values($cumulativeCounts);



    $sql = "
        SELECT o.id, o.created_at, o.status, u.name AS user_name
        FROM orders o
        LEFT JOIN user_information u ON o.customer_id = u.user_id
        ORDER BY o.created_at DESC
    ";
    $result = $conn->query($sql);

    function statusBadge($status) {
        switch(strtolower($status)) {
            case 'pending proof':
                return '<span class="badge bg-warning text-dark"><i class="fas fa-clock"></i>Menunggu Verifikasi Admin</span>';
            case 'Work On Progress':
                return '<span class="badge bg-info"><i class="fas fa-check-circle"></i>Worker Mengerjakan</span>';
            case 'completed':
                return '<span class="badge bg-success"><i class="fas fa-thumbs-up"></i> Selesai</span>';
            case 'declined':
                return '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Ditolak</span>';
            case 'komplain':
                return '<span class="badge bg-danger"><i class="fas fa-exclamation-triangle"></i>Customer Komplain</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }


} catch (Exception $e) {
    echo "Query failed: " . $e->getMessage();
    exit();
}
?>




<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .stat-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        canvas {
            max-height: 250px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->    
            <?php require './sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2 class="mb-4">Dashboard</h2>
                <!-- Statistik Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3><?= htmlspecialchars($total_users) ?></h3>
                                    <p class="mb-0">Total Pengguna</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-success text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3><?= htmlspecialchars($total_services) ?></h3>
                                    <p class="mb-0">Total Jasa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-info text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3><?= htmlspecialchars($totalOrders) ?></h3>
                                    <p class="mb-0">Total Transaksi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Grafik Statistik 1</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height: 300px;">
                                    <canvas id="statisticsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Grafik Statistik 2</h5>
                            </div>
                            <div class="card-body">
                                <div style="height: 300px;">
                                    <canvas id="statisticsChart1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Recent Activities -->
                <div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Aktivitas</th>
                                <th>Pengguna</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                        <td>Transaksi baru #TRX<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></td>
                                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                                        <td><?= statusBadge($row['status']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center">Tidak ada aktivitas terbaru.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>


    <script>
        // Grafik Statistik 1 (Line chart)
        const labels = <?= json_encode($labels); ?>;
        const totalOrders = <?= json_encode($counts); ?>;

        var ctx = document.getElementById('statisticsChart').getContext('2d');
        var statisticsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Order',
                    data: totalOrders,
                    borderColor: '#0D6EFD',
                    fill: false,
                    tension: 0.3 // optional, for smooth curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1,
                        ticks: {
                            precision: 0 // force integers
                        }
                    }
                }
            }
        });

        // Grafik Statistik 2 (Pie chart)
        const total_users = <?= json_encode($total_users); ?>;
        const total_services = <?= json_encode($total_services); ?>;
        const totalOrderss = <?= json_encode($totalOrders); ?>;
        var ctx1 = document.getElementById('statisticsChart1').getContext('2d');
        var statisticsChart1 = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: ['Total Pengguna', 'Total Jasa', 'Total Transaksi'],
            datasets: [{
                data: [total_users, total_services, totalOrderss],
                backgroundColor: ['#FF5733', '#33FF57', '#3357FF']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.location.href = './admin.php';
    };
    </script>
</body>

</html>