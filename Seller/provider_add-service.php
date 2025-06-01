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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tambah Layanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>

</head>

<body class="bg-gray-100 font-sans">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <?php require '../header.php'; ?>


        <div class="flex flex-1 flex-col md:flex-row">
            <!-- Sidebar -->
            <?php require './sidebar.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <!-- Tabs -->
                    <div class="flex gap-6 border-b pb-4 mb-6">
                        <a href="provider-dashboard.php" class="text-gray-500 hover:text-blue-600">Profil Penyedia Jasa</a>
                        <a href="provider_user-reviews.php" class="text-gray-500 hover:text-blue-600">User Review</a>
                    </div>

                    <h2 class="text-2xl font-bold mb-6">Tambah Layanan</h2>

                    <form method="POST" id="serviceForm" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="service_name" class="block text-gray-700">Nama Layanan</label>
                            <input type="text" name="service_name" id="service_name"
                                class="w-full p-3 border rounded-md" required>
                        </div>

                        <div class="mb-4">
                            <label for="service_description" class="block text-gray-700">Deskripsi Layanan</label>
                            <textarea name="service_description" id="service_description"
                                class="w-full p-3 border rounded-md" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="service_price" class="block text-gray-700">Harga Layanan</label>
                            <input type="number" name="service_price" id="service_price"
                                class="w-full p-3 border rounded-md" required>
                        </div>

                        <div class="mb-4">
                            <label for="service_type" class="block text-gray-700">Kategori Layanan</label>
                            <select name="service_type" id="service_type" class="w-full p-3 border rounded-md" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Desain">Desain</option>
                                <option value="Fotografi">Fotografi</option>
                                <option value="Teknologi">Teknologi</option>
                                <option value="Kebersihan">Kebersihan</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>


                        <div class="mb-4">
                            <label for="service_image" class="block text-gray-700">Gambar Layanan</label>
                            <input type="file" name="service_image" id="service_image"
                                class="w-full p-3 border rounded-md" accept="image/*" required>
                            <div class="mt-4">
                                <img id="imagePreview" class="w-96 h-96 object-contain rounded-md hidden border" style="max-width:100%; max-height:100%;" />
                            </div>

                        </div>

                        <div class="text-center">
                            <button type="button" id="submitService"
                                class="bg-gray-800 text-white px-6 py-3 rounded-full shadow hover:bg-gray-700 transition">
                                Tambah Layanan
                            </button>
                        </div>
                    </form>

                    <div id="serviceList" class="space-y-4 mt-6">
                        <!-- Services will be loaded here by JS -->
                    </div>
                </div>
            </main>
        </div>
        <!-- Footer -->
        <?php require '../User/footer.php'; ?>
    </div>

    <script>
        // Function to load services
        function loadServices() {
            fetch('../database/seller-service.php', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'getservice',
                }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .then(response => response.json())
            .then(data => {
                const serviceList = document.getElementById('serviceList');
                serviceList.innerHTML = ''; // Clear existing content

                if (data.success && data.services) {
                    data.services.forEach(service => {
                        // Determine badge class based on status
                        let badgeClass = '';
                        if (service.status === 'approved') {
                            badgeClass = 'bg-green-100 text-green-800';
                        } else if (service.status === 'pending') {
                            badgeClass = 'bg-yellow-100 text-yellow-800';
                        } else if (service.status === 'rejected') {
                            badgeClass = 'bg-red-100 text-red-800'; 
                        }

                        // Only include the image <img> if image data exists and is non-empty
                        const imgHTML = service.service_image
                            ? `<img src="${service.service_image}" alt="Preview" class="w-24 h-24 object-cover rounded border border-gray-300 mr-4">`
                            : '';

                        const serviceDiv = document.createElement('div');
                        serviceDiv.classList.add('bg-gray-50', 'p-4', 'rounded-lg', 'border', 'border-gray-200');

                        // If image exists, use flex layout with gap, else just normal block for text
                        serviceDiv.innerHTML = `
                        <div class="flex justify-between items-center">
                            <div class="flex items-start ${imgHTML ? 'space-x-4' : ''} flex-1">
                            ${imgHTML}
                            <div>
                                <h4 class="font-semibold text-lg">${service.service_name}</h4>
                                <p class="text-gray-600 mt-1">${service.service_description}</p>
                                <p class="text-gray-800 font-medium mt-2">Rp ${parseInt(service.service_price).toLocaleString()}</p>
                            </div>
                            </div>
                            <div class="flex flex-col items-end space-y-2 ml-4">
                            <span class="px-4 py-1 rounded-full text-sm font-medium ${badgeClass}">
                                Status: ${service.status.charAt(0).toUpperCase() + service.status.slice(1)}
                            </span>
                            <button onclick="deleteService(${service.service_id})" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                            </div>
                        </div>
                        `;
                        serviceList.appendChild(serviceDiv);
                    });
                } else {
                    serviceList.innerHTML = `<p class="text-center text-gray-500">No services found.</p>`;
                }
            })
            .catch(error => console.error('Error loading services:', error));
        }


        let cropper;
        document.getElementById('service_image').addEventListener('change', function (event) {
            const preview = document.getElementById('imagePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');

                    // Destroy previous cropper instance if exists
                    if (cropper) {
                        cropper.destroy();
                    }
                    
                    // Initialize cropper
                    cropper = new Cropper(preview, {
                        aspectRatio: 1, // square crop (change if needed)
                        viewMode: 1,
                        autoCropArea: 1,
                        movable: true,
                        zoomable: true,
                        rotatable: false,
                        scalable: false,
                        background: false,
                    });
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.classList.add('hidden');
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            }
        });




        // Function to add service
        document.getElementById('submitService').addEventListener('click', function () {
            const serviceName = document.getElementById('service_name').value.trim();
            const serviceDescription = document.getElementById('service_description').value.trim();
            const servicePrice = document.getElementById('service_price').value.trim();
            const serviceType = document.getElementById('service_type').value.trim(); // NEW

            if (!serviceName || !servicePrice || !serviceType) {
                alert('Nama layanan, kategori, dan harga wajib diisi');
                return;
            }

            if (!cropper) {
                alert('Gambar layanan wajib diunggah dan dipotong');
                return;
            }

            // Get cropped canvas and convert to base64
            const canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500,
                imageSmoothingQuality: 'high',
            });

            if (!canvas) {
                alert('Gagal memproses gambar.');
                return;
            }

            // Convert canvas to base64 string
            const base64Image = canvas.toDataURL('image/jpeg', 0.9);

            // Prepare data to send
            fetch('../database/seller-service.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'add',
                    nama_layanan: serviceName,
                    deskripsi: serviceDescription,
                    harga: servicePrice,
                    kategori: serviceType, // NEW
                    image_base64: base64Image
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Service added successfully');
                    loadServices(); // Optional: refresh list
                    document.getElementById('serviceForm').reset();
                    document.getElementById('imagePreview').src = '';
                    document.getElementById('imagePreview').classList.add('hidden');
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                } else {
                    alert('Gagal menambahkan layanan: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(() => alert('Terjadi kesalahan saat mengirim data.'));
        });

        // Function to delete service
        function deleteService(serviceId) {
            if (confirm('Apakah Anda yakin ingin menghapus layanan ini?')) {
                fetch('../database/seller-service.php', {
                        method: 'POST',
                        body: new URLSearchParams({
                            action: 'delete',
                            service_id: serviceId
                        }),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Service deleted successfully');
                            loadServices(); // Reload services
                        } else {
                            alert('Error deleting service');
                        }
                    });
            }
        }
        loadServices();

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