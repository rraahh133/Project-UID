<?php
session_start();
include('../database/service_functions.php');



$status_filter = $_GET['status'] ?? 'all';

$valid_statuses = ['all', 'pending proof', 'verified proof', 'Work On Progress', 'completed', 'declined'];
if (!in_array($status_filter, $valid_statuses)) {
    $status_filter = 'all';
}

$whereClause = '';
$params = [];
$types = '';

if ($status_filter !== 'all') {
    $whereClause = "WHERE status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

$sql = "
    SELECT id, customer_id, seller_id, service_id, price, payment_proof, seller_proof, status, created_at
    FROM orders
    $whereClause
    ORDER BY created_at DESC
";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}


if (isset($_GET['verify'])) {
    $id = (int)$_GET['verify'];

    $stmt = $conn->prepare("UPDATE orders SET status = 'verified proof' WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect after update to avoid resubmission
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit;
    } else {
        echo "Error verifying transaction.";
    }
}

if (isset($_GET['cancel'])) {
    $id = (int)$_GET['cancel'];

    $stmt = $conn->prepare("UPDATE orders SET status = 'declined' WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit;
    } else {
        echo "Error cancelling transaction.";
    }
}
?>
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
            <?php require './sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 main-content">
    <?php
    $statuses = [
        'all' => 'Semua',
        'pending proof' => 'pending proof',
        'verified proof' => 'verified proof',
        'Work On Progress' => 'Work On Progress',
        'completed' => 'Selesai',
        'declined' => 'Ditolak',
        'komplain' => 'Bermasalah'
    ];

    $colorMap = [
        'all' => 'secondary',
        'pending proof' => 'warning',
        'verified proof' => 'info',
        'Work On Progress' => 'primary',
        'completed' => 'success',
        'declined' => 'danger',
        'komplain' => 'danger'
    ];
    ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Transaksi</h2>
        <div class="btn-group">
            <?php foreach ($statuses as $key => $label): ?>
                <a href="?status=<?= $key ?>"
                class="btn btn-outline-<?= $colorMap[$key] ?? 'primary' ?> <?= ($status_filter === $key) ? 'active' : '' ?>">
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row g-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($transactions as $trx):
                $cardColor = match ($trx['status']) {
                    'pending proof' => 'bg-yellow-50 border-yellow-300',
                    'Work On Progress' => 'bg-yellow-50 border-yellow-300',
                    'verified proof' => 'bg-green-50 border-green-300',
                    'completed' => 'bg-green-50 border-green-300',
                    'declined' => 'bg-red-50 border-red-300',
                    'komplain' => 'bg-red-50 border-red-300',
                    default => 'bg-gray-50 border-gray-200'
                };

                $badgeColor = match ($trx['status']) {
                    'pending proof' => 'bg-yellow-100 text-yellow-800',
                    'Work On Progress' => 'bg-yellow-100 text-yellow-800',
                    'verified proof' => 'bg-green-100 text-green-800',
                    'completed' => 'bg-green-100 text-green-800',
                    'declined' => 'bg-red-100 text-red-800',
                    'komplain' => 'bg-red-100 text-red-800',
                    default => 'bg-gray-100 text-gray-800'
                };

                // ✅ Get user data
                $customer = getUserData($conn, $trx['customer_id']);
                $seller = getUserData($conn, $trx['seller_id']);
                $service_information = fetchServiceBySeller($conn, $trx['service_id'], $trx['seller_id']);
            ?>
            <div class="rounded-xl shadow-md <?= $cardColor ?> border p-6 transition hover:shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-lg font-semibold text-gray-800">Transaksi #<?= $trx['id'] ?></h5>
                    <span class="px-3 py-1 text-sm rounded-full font-medium <?= $badgeColor ?>">
                        <?= ucfirst($trx['status']) ?>
                    </span>
                </div>

                <ul class="text-sm text-gray-700 space-y-1">
                    <?php if (!empty($trx['payment_proof'])): ?>
                        <li>
                            <strong>Bukti Pembayaran:</strong><br>
                            <a href="../database/view_payment_proof.php?id=<?= $trx['id'] ?>" target="_blank" rel="noopener noreferrer" title="Lihat Bukti Pembayaran">
                                <img src="data:image/jpeg;base64,<?= $trx['payment_proof'] ?>" alt="Bukti Pembayaran" style="max-width: 200px; border: 1px solid #ccc; cursor: pointer;" />
                            </a>
                        </li>
                    <?php endif; ?>

                    <li><strong>Service ID:</strong> <?= $trx['service_id'] ?></li>
                    <li><strong>Pelanggan:</strong> <?= $customer['info_name'] ?? 'Unknown' ?> (ID: <?= $trx['customer_id'] ?>)</li>
                    <li><strong>Penjual:</strong> <?= $seller['info_name'] ?? 'Unknown' ?> (ID: <?= $trx['seller_id'] ?>)</li>
                    <li><strong>Total:</strong> Rp <?= number_format($service_information['service_price'], 0, ',', '.') ?></li>
                    <li><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($trx['created_at'])) ?></li>
                </ul>

                <?php if ($trx['status'] === 'pending proof'): ?>
                <div class="flex justify-end gap-2 mt-4">
                    <a href="?verify=<?= $trx['id'] ?>"
                    class="inline-flex items-center gap-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition">
                        <i class="fas fa-check"></i> Verifikasi
                    </a>
                    <a href="?cancel=<?= $trx['id'] ?>"
                    class="inline-flex items-center gap-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                        <i class="fas fa-times"></i> Batalkan
                    </a>
                </div>

                
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>


        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>