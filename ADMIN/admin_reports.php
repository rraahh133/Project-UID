<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <!-- Sidebar -->    
            <?php require './sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Laporan Bulanan</h2>
                    <form method="GET" class="d-flex gap-2">
                        <input type="month" class="form-control" name="month" value="<?php echo $month; ?>">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>

                <!-- Statistik Bulanan -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card bg-primary text-white">
                            <h3>156</h3>
                            <p class="mb-0">Total Transaksi</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-success text-white">
                            <h3>Rp 25.000.000</h3>
                            <p class="mb-0">Total Pendapatan</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-info text-white">
                            <h3>142</h3>
                            <p class="mb-0">Transaksi Selesai</p>
                        </div>
                    </div>
                </div>

                <!-- Grafik -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Grafik Transaksi Harian</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="dailyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Services & Providers -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Top 5 Jasa</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Jasa</th>
                                                <th>Transaksi</th>
                                                <th>Pendapatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Jasa Desain Logo</td>
                                                <td>45</td>
                                                <td>Rp 22.500.000</td>
                                            </tr>
                                            <tr>
                                                <td>Jasa Fotografi</td>
                                                <td>38</td>
                                                <td>Rp 19.000.000</td>
                                            </tr>
                                            <tr>
                                                <td>Jasa Video Editing</td>
                                                <td>32</td>
                                                <td>Rp 16.000.000</td>
                                            </tr>
                                            <tr>
                                                <td>Jasa Copywriting</td>
                                                <td>28</td>
                                                <td>Rp 14.000.000</td>
                                            </tr>
                                            <tr>
                                                <td>Jasa Web Development</td>
                                                <td>25</td>
                                                <td>Rp 12.500.000</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Top 5 Penyedia</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Penyedia</th>
                                                <th>Transaksi</th>
                                                <th>Pendapatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Alex Designer</td>
                                                <td>52</td>
                                                <td>Rp 26.000.000</td>
                                            </tr>
                                            <tr>
                                                <td>Lisa Photographer</td>
                                                <td>45</td>
                                                <td>Rp 22.500.000</td>
                                            </tr>
                                            <tr>
                                                <td>Emma Editor</td>
                                                <td>38</td>
                                                <td>Rp 19.000.000</td>
                                            </tr>
                                            <tr>
                                                <td>Sophia Writer</td>
                                                <td>35</td>
                                                <td>Rp 17.500.000</td>
                                            </tr>
                                            <tr>
                                                <td>Mike Developer</td>
                                                <td>30</td>
                                                <td>Rp 15.000.000</td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data dummy untuk grafik harian
        const dates = ['1 Mar', '2 Mar', '3 Mar', '4 Mar', '5 Mar', '6 Mar', '7 Mar', '8 Mar', '9 Mar', '10 Mar', 
                      '11 Mar', '12 Mar', '13 Mar', '14 Mar', '15 Mar', '16 Mar', '17 Mar', '18 Mar', '19 Mar', '20 Mar',
                      '21 Mar', '22 Mar', '23 Mar', '24 Mar', '25 Mar', '26 Mar', '27 Mar', '28 Mar', '29 Mar', '30 Mar', '31 Mar'];
        
        const transactions = [5, 8, 6, 9, 7, 10, 8, 12, 9, 11, 
                            7, 8, 9, 10, 12, 8, 9, 11, 10, 13,
                            9, 10, 8, 11, 12, 9, 10, 11, 13, 12, 14];
        
        const revenue = [2500000, 4000000, 3000000, 4500000, 3500000, 5000000, 4000000, 6000000, 4500000, 5500000,
                        3500000, 4000000, 4500000, 5000000, 6000000, 4000000, 4500000, 5500000, 5000000, 6500000,
                        4500000, 5000000, 4000000, 5500000, 6000000, 4500000, 5000000, 5500000, 6500000, 6000000, 7000000];

        // Data untuk grafik
        const ctx = document.getElementById('dailyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: transactions,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    yAxisID: 'y'
                }, {
                    label: 'Pendapatan (Rp)',
                    data: revenue,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Jumlah Transaksi'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Pendapatan (Rp)'
                        },
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html> 