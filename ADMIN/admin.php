<?php
include "../database/db_connect.php"; // Include your PDO connection
session_start();

$user_id = $_SESSION["user_id"];

try {
    $stmt = $pdo->prepare("
SELECT users.*, user_information.*
FROM users
INNER JOIN user_information ON users.user_id = user_information.user_id
WHERE users.user_id = :id
");
    $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt_total = $pdo->prepare("SELECT COUNT(*) AS total_users FROM users");
    $stmt_total->execute();
    $total_users = $stmt_total->fetch(PDO::FETCH_ASSOC)["total_users"];

    if (!$user) {
        header("Location: ./login_user.php");
        exit();
    }
} catch (PDOException $e) {
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
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <h3 class="mb-4">Admin Panel</h3>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="admin.php" class="nav-link active">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="admin_users.php" class="nav-link">
                            <i class="fas fa-users me-2"></i> Manajemen Pengguna
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="admin_products.php" class="nav-link">
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
                <h2 class="mb-4">Dashboard</h2>

                <!-- Statistik Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3>250</h3>
                                    <p class="mb-0">Total Pengguna</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-success text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3>45</h3>
                                    <p class="mb-0">Total Jasa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-info text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3>180</h3>
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
                                            <tr>
                                                <td>15/03/2024 14:30</td>
                                                <td>Transaksi baru #TRX001</td>
                                                <td>John Doe</td>
                                                <td><span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Menunggu</span></td>
                                            </tr>
                                            <tr>
                                                <td>15/03/2024 13:45</td>
                                                <td>Jasa baru ditambahkan</td>
                                                <td>Sarah Smith</td>
                                                <td><span class="badge bg-info"><i class="fas fa-check-circle"></i> Aktif</span></td>
                                            </tr>
                                            <tr>
                                                <td>15/03/2024 12:20</td>
                                                <td>Pembayaran diterima</td>
                                                <td>Mike Johnson</td>
                                                <td><span class="badge bg-success"><i class="fas fa-thumbs-up"></i> Selesai</span></td>
                                            </tr>
                                            <tr>
                                                <td>15/03/2024 11:15</td>
                                                <td>Pengguna baru mendaftar</td>
                                                <td>Emma Wilson</td>
                                                <td><span class="badge bg-info"><i class="fas fa-user-plus"></i> Aktif</span></td>
                                            </tr>
                                            <tr>
                                                <td>15/03/2024 10:00</td>
                                                <td>Jasa diperbarui</td>
                                                <td>David Brown</td>
                                                <td><span class="badge bg-info"><i class="fas fa-sync-alt"></i> Aktif</span></td>
                                            </tr>
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
        var ctx = document.getElementById('statisticsChart').getContext('2d');
        var statisticsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Pendapatan',
                    data: [0, 10, 5, 15, 20, 30],
                    borderColor: '#0D6EFD',
                    fill: false
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
                        beginAtZero: true
                    }
                }
            }
        });

        // Grafik Statistik 2 (Pie chart)
        var ctx1 = document.getElementById('statisticsChart1').getContext('2d');
        var statisticsChart1 = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['A', 'B', 'C', 'D'],
                datasets: [{
                    data: [30, 20, 10, 40],
                    backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#F1C40F']
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
    </script>
</body>

</html>