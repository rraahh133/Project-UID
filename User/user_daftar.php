<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: ../auth.php");
    exit;
}
include('../database/service_functions.php');
$user = getUserData($conn, $_SESSION['user_id']);
if (!$user) {
    header("Location: ../auth.php");
    exit;
}
$addresses = executeQuery($conn, "SELECT * FROM user_addresses WHERE user_id = ?", 'i', [$_SESSION['user_id']]);
?>


<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Alamat - SiBantu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
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
                <img src="<?= $user['profile_picture'] ? 'data:image/jpeg;base64,' . $user['profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" class="rounded-full w-10 h-10 border-2 border-white" />
            </div>
        </header>

        <div class="flex flex-1 flex-col md:flex-row">
            <!-- Sidebar -->
            <aside class="w-full md:w-64 bg-white p-6 shadow-md">
                <div class="flex items-center gap-3 mb-6">
                    <img src="<?= $user['profile_picture'] ? 'data:image/jpeg;base64,' . $user['profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" class="rounded-full w-12 h-12">
                    <div>
                        <h2 class="text-lg font-semibold"><?= htmlspecialchars($user['name'] ?? 'User') ?></h2>
                        <p class="text-gray-500 text-sm">Member Silver</p>
                    </div>
                </div>

                <!-- Dropdown -->
                <div>
                    <button onclick="toggleDropdown('transaksiDropdown')" class="w-full bg-gray-100 px-4 py-2 rounded flex justify-between items-center text-gray-700 font-medium hover:bg-gray-200">
                        Transaksi
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div id="transaksiDropdown" class="hidden mt-2">
                        <a href="user_status.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Status Pembayaran</a>
                        <a href="user_RiwayatTransaksi.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Riwayat Transaksi</a>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <!-- Tabs -->
                    <div class="flex gap-6 border-b pb-4 mb-6">
                        <a href="user_dashboard.php" class="text-gray-500 hover:text-blue-600">Biodata Diri</a>
                        <a href="user_daftar.php" class="text-blue-600 font-semibold">Daftar Alamat</a>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Daftar Alamat</h2>
                        <div>
                            <button class="bg-gray-800 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-700 transition duration-300" onclick="openAddModal()">
                                Tambah Alamat
                            </button>
                        </div>
                    </div>
                    <div id="addressList">
                    <?php
                    if ($addresses) {
                        foreach ($addresses as $address) {
                    ?>
                        <div class="bg-white p-6 rounded shadow mb-4" data-id="<?php echo $address['id']; ?>">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">
                                    <?php echo trim(strtolower($address['keterangan'])) === 'rumah' ? 'Alamat Rumah' : 'Alamat Kantor'; ?>
                                </h2>
                                <div>
                                    <button class="bg-gray-800 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-700 transition duration-300 mr-2" onclick="editAddress(this)">Edit Alamat</button>
                                    <button class="bg-red-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-red-600 transition duration-300" onclick="confirmDeleteAddress(this)">Hapus Alamat</button>
                                </div>
                            </div>
                            <div class="address-container space-y-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user text-gray-600"></i>
                                    <p class="font-semibold">Nama Penerima:</p>
                                    <p class="addressName"><?php echo $address['nama_penerima']; ?></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-phone text-gray-600"></i>
                                    <p class="font-semibold">Nomor Telepon:</p>
                                    <p class="addressPhone"><?php echo $address['nomor_telepon']; ?></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-gray-600"></i>
                                    <p class="font-semibold">Alamat Lengkap:</p>
                                    <p class="addressText"><?php echo $address['alamat_lengkap']; ?></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                    <p class="font-semibold">Keterangan:</p>
                                    <p class="addressNote"><?php echo $address['keterangan']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    }
                    ?>
                </div>
                </div>
            </main>
        </div>

        <?php require 'footer.php'; ?>

    </div>

    <!-- Edit Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden" id="editModal">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
            <h2 class="text-xl font-bold mb-4">Edit Alamat</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">Nama Penerima</label>
                    <input class="w-full p-2 border border-gray-300 rounded" id="editNameInput" type="text" />
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Nomor Telepon</label>
                    <input class="w-full p-2 border border-gray-300 rounded" id="editPhoneInput" type="tel" />
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea class="w-full p-2 border border-gray-300 rounded" id="editAddressInput" rows="3"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Keterangan</label>
                    <textarea class="w-full p-2 border border-gray-300 rounded" id="editNoteInput" rows="2" placeholder="Contoh: Rumah warna putih, pagar hitam"></textarea>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button class="bg-gray-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-600 transition duration-300 mr-2" onclick="closeModal()">
                    Batal
                </button>
                <button class="bg-blue-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-600 transition duration-300" onclick="confirmEditAddress()">
                    Simpan
                </button>
            </div>
        </div>
    </div>
    <!-- Add Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden" id="addModal">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
            <h2 class="text-xl font-bold mb-4">Tambah Alamat</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">Nama Penerima</label>
                    <input class="w-full p-2 border border-gray-300 rounded" id="addNameInput" type="text" />
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Nomor Telepon</label>
                    <input class="w-full p-2 border border-gray-300 rounded" id="addPhoneInput" type="tel" />
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea class="w-full p-2 border border-gray-300 rounded" id="addAddressInput" rows="3"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Keterangan</label>
                    <textarea class="w-full p-2 border border-gray-300 rounded" id="addNoteInput" rows="2" placeholder="Contoh: Rumah warna putih, pagar hitam"></textarea>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button class="bg-gray-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-600 transition duration-300 mr-2" onclick="closeAddModal()">
                    Batal
                </button>
                <button class="bg-green-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-green-600 transition duration-300" onclick="addAddress()">
                    Tambah
                </button>
            </div>
        </div>
    </div>
    <!-- Confirmation Modal with Edit Summary -->
    <div class="fixed inset-0 bg-gray-700 bg-opacity-60 flex items-center justify-center hidden z-50" id="confirmModal">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-11/12 md:w-1/3">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Konfirmasi Perubahan</h2>
            <p class="text-gray-600 mb-6 text-center">Silakan tinjau kembali informasi berikut sebelum menyimpan perubahan.</p>

            <!-- Edit Summary -->
            <div id="editSummary" class="bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-3 mb-6">
            <div class="flex items-start gap-2">
                <i class="fas fa-user text-gray-500 mt-1"></i>
                <div>
                <p class="text-sm text-gray-500">Nama Penerima</p>
                <p class="font-semibold text-gray-800" id="summaryName">-</p>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <i class="fas fa-phone text-gray-500 mt-1"></i>
                <div>
                <p class="text-sm text-gray-500">Nomor Telepon</p>
                <p class="font-semibold text-gray-800" id="summaryPhone">-</p>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <i class="fas fa-map-marker-alt text-gray-500 mt-1"></i>
                <div>
                <p class="text-sm text-gray-500">Alamat Lengkap</p>
                <p class="font-semibold text-gray-800" id="summaryAddress">-</p>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <i class="fas fa-info-circle text-gray-500 mt-1"></i>
                <div>
                <p class="text-sm text-gray-500">Keterangan</p>
                <p class="font-semibold text-gray-800" id="summaryNote">-</p>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end">
        <button
            class="bg-gray-400 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-500 transition duration-300 mr-2"
            onclick="closeConfirmModal()">
            Batal
        </button>
        <button
            class="bg-blue-600 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-700 transition duration-300"
            onclick="saveAddress()">
            Ya, Simpan
        </button>
        </div>
    </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden"
        id="deleteConfirmModal">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
            <h2 class="text-xl font-bold mb-4">
                Konfirmasi
            </h2>
            <p class="mb-4">Apakah Anda yakin ingin menghapus alamat ini?</p>
            <div class="flex justify-end">
                <button
                    class="bg-gray-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-600 transition duration-300 mr-2"
                    onclick="closeDeleteConfirmModal()">
                    Batal
                </button>
                <button
                    class="bg-red-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-red-600 transition duration-300"
                    onclick="deleteAddress()">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
    <!-- Notification -->
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg hidden" id="notification">
        Alamat berhasil diubah!
    </div>
    <script>
        let currentEditElement = null;
        let currentDeleteElement = null;

        function editAddress(button) {
            // Find the closest parent element that contains the address data
            const addressCard = button.closest('.bg-white');
            currentEditElement = addressCard;
            
            // Get the address ID from the data-id attribute of the addressCard
            const addressId = addressCard.getAttribute('data-id');

            // Set the address ID into the modal so it can be used later
            document.getElementById('editModal').setAttribute('data-id', addressId);

            // Populate the input fields in the edit modal with the current address data
            document.getElementById('editNameInput').value = addressCard.querySelector('.addressName').textContent;
            document.getElementById('editPhoneInput').value = addressCard.querySelector('.addressPhone').textContent;
            document.getElementById('editAddressInput').value = addressCard.querySelector('.addressText').textContent;
            document.getElementById('editNoteInput').value = addressCard.querySelector('.addressNote').textContent;
            
            // Show the edit modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Function to confirm editing an address
        function confirmEditAddress() {
            const name = document.getElementById('editNameInput').value;
            const phone = document.getElementById('editPhoneInput').value;
            const address = document.getElementById('editAddressInput').value;
            const note = document.getElementById('editNoteInput').value;

            // Set values in the summary UI
            document.getElementById('summaryName').textContent = name;
            document.getElementById('summaryPhone').textContent = phone;
            document.getElementById('summaryAddress').textContent = address;
            document.getElementById('summaryNote').textContent = note || '-';

            // Show summary modal
            document.getElementById('editSummary').classList.remove('hidden');

            // Show confirmation modal
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        // Function to close the confirmation modal
        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        // Function to save the address after editing
        async function saveAddress() {
            if (!currentEditElement) return;

            // Get the updated values from the summary
            const updatedName = document.getElementById('summaryName').textContent;
            const updatedPhone = document.getElementById('summaryPhone').textContent;
            const updatedAddress = document.getElementById('summaryAddress').textContent;
            const updatedNote = document.getElementById('summaryNote').textContent;
            
            // Get the address ID
            const addressId = currentEditElement.getAttribute('data-id');

            // Ensure all necessary fields are filled
            if (updatedName && updatedPhone && updatedAddress && addressId) {
                const formData = new FormData();
                formData.append("action", "update");
                formData.append("address_id", addressId);
                formData.append("nama_penerima", updatedName);
                formData.append("nomor_telepon", updatedPhone);
                formData.append("alamat_lengkap", updatedAddress);
                formData.append("keterangan", updatedNote);

                try {
                    const response = await fetch('../database/address.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();
                    console.log(data);

                    // If the update is successful, update the UI
                    if (data.success) {
                        // Update UI with the new address details
                        currentEditElement.querySelector('.addressName').textContent = updatedName;
                        currentEditElement.querySelector('.addressPhone').textContent = updatedPhone;
                        currentEditElement.querySelector('.addressText').textContent = updatedAddress;
                        currentEditElement.querySelector('.addressNote').textContent = updatedNote;

                        // Update the title based on note
                        const title = updatedNote === 'Rumah' ? 'Alamat Rumah' : 'Alamat Kantor';
                        currentEditElement.querySelector('h2').textContent = title;

                        // Hide all modals
                        document.getElementById('editModal').classList.add('hidden');
                        document.getElementById('editSummary').classList.add('hidden');
                        document.getElementById('confirmModal').classList.add('hidden');

                        // Show success notification
                        showNotification('Alamat berhasil diperbarui!');
                    } else {
                        alert("Gagal memperbarui alamat: " + data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan saat memperbarui alamat.");
                }
            } else {
                alert("Semua kolom harus diisi!");
            }
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        async function addAddress() {
            const newName = document.getElementById('addNameInput').value;
            const newPhone = document.getElementById('addPhoneInput').value;
            const newAddress = document.getElementById('addAddressInput').value;
            const newNote = document.getElementById('addNoteInput').value; // keterangan

            if (newName && newPhone && newAddress) {
                // Prepare form data to send to the server
                const formData = new FormData();
                formData.append("action", "add");
                formData.append("nama_penerima", newName);
                formData.append("nomor_telepon", newPhone);
                formData.append("alamat_lengkap", newAddress);
                formData.append("keterangan", newNote);

                    // Send the data to the server using fetch
                    const response = await fetch('../database/address.php', {
                        method: 'POST',
                        body: formData
                    });

                    // Check if the response is successful
                    const data = await response.json();
                    console.log(response); // Log the response data

                    if (data.success) {
                        // Successfully added the address, now update the UI
                        const addressList = document.getElementById('addressList');
                        const addressTitle = newNote === 'Rumah' ? 'Alamat Rumah' : 'Alamat Kantor'; // Set title based on keterangan

                        const newAddressCard = `
                            <div class="bg-white p-6 rounded shadow mb-4" data-id="${data.address_id}">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-bold">${addressTitle}</h2>  <!-- Dynamic title based on keterangan -->
                                    <div>
                                        <button class="bg-gray-800 text-white px-4 py-2 rounded-full shadow-md hover:bg-gray-700 transition duration-300 mr-2" onclick="editAddress(this)">Edit Alamat</button>
                                        <button class="bg-red-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-red-600 transition duration-300" onclick="confirmDeleteAddress(this)">Hapus Alamat</button>
                                    </div>
                                </div>
                                <div class="address-container space-y-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-user text-gray-600"></i>
                                        <p class="font-semibold">Nama Penerima:</p>
                                        <p class="addressName">${newName}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-phone text-gray-600"></i>
                                        <p class="font-semibold">Nomor Telepon:</p>
                                        <p class="addressPhone">${newPhone}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-gray-600"></i>
                                        <p class="font-semibold">Alamat Lengkap:</p>
                                        <p class="addressText">${newAddress}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-info-circle text-gray-600"></i>
                                        <p class="font-semibold">Keterangan:</p>
                                        <p class="addressNote">${newNote}</p>  <!-- Display keterangan -->
                                    </div>
                                </div>
                            </div>
                        `;
                        addressList.insertAdjacentHTML('beforeend', newAddressCard);
                        showNotification('Alamat berhasil ditambahkan!');  // Assuming you have a function to show notifications
                        closeAddModal();  // Assuming you have a function to close the modal
                    } else {
                        console.error("Failed to add address:", data.message);
                        alert("Gagal menambahkan alamat: " + data.message);
                    }
            } else {
                alert("Harap isi semua kolom yang diperlukan!");
            }
        }

        function confirmDeleteAddress(button) {
            currentDeleteElement = button.closest('.bg-white');
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
        }

        function deleteAddress() {
            if (currentDeleteElement) {
                const addressId = currentDeleteElement.getAttribute('data-id');

                // Send the delete request to the backend to remove the address from the database
                const formData = new FormData();
                formData.append("action", "delete");
                formData.append("address_id", addressId);

                // Perform the AJAX request
                fetch('../database/address.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the address from the DOM
                        currentDeleteElement.remove();
                        closeDeleteConfirmModal();
                        showNotification('Alamat berhasil dihapus!');
                    } else {
                        showNotification('Gagal menghapus alamat: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat menghapus alamat.');
                });
            }
        }


        function closeDeleteConfirmModal() {
            document.getElementById('deleteConfirmModal').classList.add('hidden');
        }

        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.remove('hidden');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }

        function toggleDropdown(id) {
            var dropdown = document.getElementById(id);
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }
    </script>
</body>
</html>