<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
                        <a href="admin_users.php" class="nav-link active">
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

            <?php
            // Sample users data (you can replace this with your database data)
            $users = [
                ['id' => 1, 'username' => 'john_doe', 'email' => 'john.doe@example.com', 'role' => 'admin'],
                ['id' => 2, 'username' => 'sarah_smith', 'email' => 'sarah.smith@example.com', 'role' => 'seller'],
                ['id' => 3, 'username' => 'mike_johnson', 'email' => 'mike.johnson@example.com', 'role' => 'user'],
                ['id' => 4, 'username' => 'emma_wilson', 'email' => 'emma.wilson@example.com', 'role' => 'seller'],
                ['id' => 5, 'username' => 'david_brown', 'email' => 'david.brown@example.com', 'role' => 'user'],
            ];
            ?>

            <!-- Main Content -->
            <div class="w-full lg:w-10/12 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Manajemen Pengguna</h2>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
                        data-modal-target="addUserModal">
                        <i class="fas fa-plus mr-2"></i>Tambah Pengguna
                    </button>
                </div>

                <!-- User Table -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto text-sm text-left text-gray-700">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-4">ID</th>
                                    <th class="px-6 py-4">Username</th>
                                    <th class="px-6 py-4">Email</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo $user['id']; ?></td>
                                    <td class="px-6 py-4"><?php echo $user['username']; ?></td>
                                    <td class="px-6 py-4"><?php echo $user['email']; ?></td>
                                    <td class="px-6 py-4"><?php echo $user['role']; ?></td>
                                    <td class="px-6 py-4">
                                        <button
                                            class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition"
                                            onclick="editUser(<?php echo $user['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" name="delete_user"
                                                class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pengguna -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" method="POST" action="add_user.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="user">User</option>
                                <option value="seller">Seller</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editUser(userId) {
            // Implementasi fungsi edit user akan segera hadir!
            alert('Fitur edit user akan segera hadir!');
        }
    </script>
</body>

</html>