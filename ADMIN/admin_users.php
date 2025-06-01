<?php
include "../database/db_connect.php"; // your MySQLi connection ($conn)
$sql = "
    SELECT 
        u.user_id,
        u.username,
        u.email,
        u.usertype,
        COALESCE(ui.name, 'Not Found') AS info_name
    FROM users u
    LEFT JOIN user_information ui ON u.user_id = ui.user_id
";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Query failed: " . mysqli_error($conn);
    exit();
}
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
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
            <?php require './sidebar.php'; ?>

            <!-- Your HTML content starts here -->
            <div class="w-full lg:w-10/12 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Manajemen Pengguna</h2>
                </div>

                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto text-sm text-left text-gray-700">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-4">ID USER</th>
                                    <th class="px-6 py-4">Username</th>
                                    <th class="px-6 py-4">Email</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4"><?= htmlspecialchars($user['user_id']) ?></td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($user['info_name']) ?></td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($user['email']) ?></td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($user['usertype']) ?></td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <!-- Tombol Edit -->
                                        <button type="button"
                                            class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal"
                                            onclick="populateEditForm('<?= $user['user_id'] ?>', '<?= htmlspecialchars($user['info_name'], ENT_QUOTES) ?>', '<?= $user['email'] ?>', '<?= $user['usertype'] ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                            <button type="button" 
                                                    class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition"
                                                    onclick="deleteUser(<?= $user['user_id'] ?>)">
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

    <!-- Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editUserForm" onsubmit="event.preventDefault(); editUser();">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Edit Pengguna</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <input type="hidden" id="edit_user_id" name="user_id">
            <div class="mb-3">
                <label for="edit_name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="edit_name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="edit_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="edit_email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="edit_role" class="form-label">Role</label>
                <select class="form-select" id="edit_role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="customer">customer</option>
                    <option value="seller">Penyedia Jasa</option>
                </select>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
        </form>
    </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteUserModalLabel">Konfirmasi Hapus</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Apakah Anda yakin ingin menghapus pengguna ini?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
        </div>
        </div>
    </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function populateEditForm(userId, name, email, role) {
        document.getElementById('edit_user_id').value = userId;
        document.getElementById('edit_name').value = name;  
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role;
    }

    function deleteUser(user_id) {
        // Simpan user_id ke variabel closure agar bisa dipakai di event listener tombol modal
        let userIdToDelete = user_id;

        // Tampilkan modal konfirmasi hapus
        const deleteModalEl = document.getElementById('deleteUserModal');
        const deleteModal = new bootstrap.Modal(deleteModalEl);
        deleteModal.show();

        // Pastikan event listener tombol hapus tidak dobel pasang
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        // Bersihkan listener lama supaya tidak dobel
        confirmBtn.replaceWith(confirmBtn.cloneNode(true));
        const newConfirmBtn = document.getElementById('confirmDeleteBtn');

        // Pasang listener baru
        newConfirmBtn.addEventListener('click', function () {
            // Jalankan proses hapus
            const formData = new FormData();
            formData.append('action', 'deleteuser');
            formData.append('user_id', userIdToDelete);

            fetch('../database/admin-user.php', {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                try {
                    const data = await response.json();
                    if (data.success) {
                        showNotification(data.message || 'Pengguna berhasil dihapus.', true);
                        location.reload();
                    } else {
                        showNotification(data.message || 'Terjadi kesalahan saat menghapus pengguna.', false);
                    }
                } catch (e) {
                    showNotification('Terjadi kesalahan saat menghapus pengguna.', false);
                }
            })
            .catch(() => {
                showNotification('Terjadi kesalahan saat menghapus pengguna.', false);
            })
            .finally(() => {
                deleteModal.hide();
            });
        });
    }


    function editUser() {
        const userId = document.getElementById('edit_user_id').value;
        const name = document.getElementById('edit_name').value;
        const email = document.getElementById('edit_email').value;
        const role = document.getElementById('edit_role').value; // pastikan id ini sesuai di HTML
        if (!name || !email || !role) {
            alert("Semua field wajib diisi.");
            return;
        }

        fetch('../database/admin-user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest' // supaya server tahu ini AJAX
            },
            body: new URLSearchParams({
                action: 'edituser',
                user_id: userId,
                name: name,
                email: email,
                role: role
            })
        })
        .then(async response => {
            try {
                const data = await response.json();
                if (data.success) {
                    showNotification('Pengguna berhasil diperbarui.', true);
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                    editModal.hide();
                    location.reload();
                } else {
                    showNotification('Gagal memperbarui: ' + (data.message || 'Unknown error'), false);
                }
            } catch (e) {
                showNotification('Gagal memperbarui data.', false);
            }
        })
        .catch(() => {
            showNotification('Gagal menghubungi server.', false);
        });
    }


    function showNotification(message, isSuccess) {
        const notification = document.createElement('div');
        notification.textContent = message;
        notification.className = `fixed top-4 right-4 text-white py-3 px-6 rounded-lg shadow-lg ${
            isSuccess ? 'bg-green-600' : 'bg-red-600'
        }`;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    </script>

</body>
</html>