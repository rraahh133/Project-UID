<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Transaksi - Admin Panel</title>
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

        .transaction-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }

        .transaction-card:hover {
            transform: translateY(-5px);
        }

        .transaction-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .badge {
            font-size: 0.9rem;
        }

        .card.transaction-card {
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card.transaction-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .bg-light-warning {
            background-color: #fff8e1;
        }

        .bg-light-success {
            background-color: #e6f4ea;
        }

        .bg-light-danger {
            background-color: #fdecea;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar p-3">
                <h3 class="mb-4">Admin Panel</h3>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="admin.php" class="nav-link"><i class="fas fa-home me-2"></i>
                            Dashboard</a></li>
                    <li class="nav-item mb-2"><a href="admin_users.php" class="nav-link"><i
                                class="fas fa-users me-2"></i> Manajemen Pengguna</a></li>
                    <li class="nav-item mb-2"><a href="admin_products.php" class="nav-link"><i
                                class="fas fa-tools me-2"></i> Manajemen Jasa</a></li>
                    <li class="nav-item mb-2"><a href="admin_transactions.php" class="nav-link active"><i
                                class="fas fa-shopping-cart me-2"></i> Transaksi</a></li>
                    <li class="nav-item mb-2"><a href="admin_reports.php" class="nav-link"><i
                                class="fas fa-chart-bar me-2"></i> Laporan</a></li>
                    <li class="nav-item mb-2"><a href="logout.php" class="nav-link text-danger"><i
                                class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manajemen Transaksi</h2>
                    <div class="btn-group">
                        <a href="?status=all"
                            class="btn btn-outline-primary <?php echo ($status_filter ?? '') === 'all' ? 'active' : ''; ?>">Semua</a>
                        <a href="?status=pending"
                            class="btn btn-outline-warning <?php echo ($status_filter ?? '') === 'pending' ? 'active' : ''; ?>">Menunggu</a>
                        <a href="?status=completed"
                            class="btn btn-outline-success <?php echo ($status_filter ?? '') === 'completed' ? 'active' : ''; ?>">Selesai</a>
                        <a href="?status=cancelled"
                            class="btn btn-outline-danger <?php echo ($status_filter ?? '') === 'cancelled' ? 'active' : ''; ?>">Dibatalkan</a>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php
                    $transactions = [
                        ['id' => 1, 'code' => 'TRX001', 'status' => 'pending', 'badge' => 'warning', 'service' => 'Jasa Desain Logo', 'customer' => 'John Doe', 'provider' => 'Alex Designer', 'total' => 500000, 'date' => '15/03/2024 14:30'],
                        ['id' => 2, 'code' => 'TRX002', 'status' => 'completed', 'badge' => 'success', 'service' => 'Jasa Fotografi', 'customer' => 'Sarah Smith', 'provider' => 'Lisa Photographer', 'total' => 1500000, 'date' => '14/03/2024 10:15'],
                        ['id' => 3, 'code' => 'TRX003', 'status' => 'pending', 'badge' => 'warning', 'service' => 'Jasa Video Editing', 'customer' => 'Mike Johnson', 'provider' => 'Emma Editor', 'total' => 750000, 'date' => '15/03/2024 09:45'],
                        ['id' => 4, 'code' => 'TRX004', 'status' => 'cancelled', 'badge' => 'danger', 'service' => 'Jasa Copywriting', 'customer' => 'David Brown', 'provider' => 'Sophia Writer', 'total' => 300000, 'date' => '13/03/2024 16:20']
                    ];

                    foreach ($transactions as $trx):
                        $cardColor = match ($trx['status']) {
                            'pending' => 'bg-yellow-50 border-yellow-300',
                            'completed' => 'bg-green-50 border-green-300',
                            'cancelled' => 'bg-red-50 border-red-300',
                            default => 'bg-gray-50 border-gray-200'
                        };

                        $badgeColor = match ($trx['status']) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    ?>
                        <div class="rounded-xl shadow-md border <?= $cardColor ?> p-6 transition hover:shadow-lg">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="text-lg font-semibold text-gray-800">Transaksi #<?= $trx['code'] ?></h5>
                                <span class="px-3 py-1 text-sm rounded-full font-medium <?= $badgeColor ?>">
                                    <?= ucfirst($trx['status']) ?>
                                </span>
                            </div>

                            <ul class="text-sm text-gray-700 space-y-1">
                                <li><strong>Jasa:</strong> <?= $trx['service'] ?></li>
                                <li><strong>Pelanggan:</strong> <?= $trx['customer'] ?></li>
                                <li><strong>Penyedia:</strong> <?= $trx['provider'] ?></li>
                                <li><strong>Total:</strong> Rp <?= number_format($trx['total'], 0, ',', '.') ?></li>
                                <li><strong>Tanggal:</strong> <?= $trx['date'] ?></li>
                            </ul>

                            <?php if ($trx['status'] === 'pending'): ?>
                            <div class="flex justify-end gap-2 mt-4">
                                <a href="?verify=<?= $trx['id'] ?>"
                                    class="inline-flex items-center gap-1 px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition">
                                    <i class="fas fa-check"></i> Verifikasi
                                </a>
                                <a href="?cancel=<?= $trx['id'] ?>"
                                    class="inline-flex items-center gap-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-md transition">
                                    <i class="fas fa-times"></i> Batalkan
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>