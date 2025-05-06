<?php
session_start();
include ('../database/db_connect.php'); // Include your PDO connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user data if needed
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM user_information WHERE user_id = :id");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Edit Biodata
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-gray-700 shadow p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">
            <a href="User_dashboard.php" class="logo">SiBantu</a>
        </h1>
        <div class="flex items-center space-x-4">
            <i class="fas fa-bell text-white"></i>
            <i class="fas fa-envelope text-white"></i>
            <img alt="User Profile" class="rounded-full" height="40" src="https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg" width="40" />
        </div>
    </header>

    <div class="container mx-auto mt-10 p-8 bg-white shadow-lg rounded-xl flex-grow">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-semibold text-gray-800">Edit Biodata</h2>
        <a href="User_dashboard.php" class="inline-flex items-center text-gray-600 hover:text-gray-800 text-lg font-semibold py-2 px-4 rounded-lg bg-gray-100 hover:bg-gray-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <form id="editBiodataForm" enctype="multipart/form-data" method="POST">
        <!-- Name Field -->
        <div class="mb-6">
            <label class="block text-lg font-medium text-gray-700 mb-2" for="name">Nama</label>
            <input class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" id="name" name="name" placeholder="Masukkan nama Anda" type="text" value="<?= htmlspecialchars($user['name']); ?>" />
        </div>

        <!-- Birthdate Field -->
        <div class="mb-6">
            <label class="block text-lg font-medium text-gray-700 mb-2" for="birthdate">Tanggal Lahir</label>
            <input class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" id="birthdate" name="birthdate" type="date" value="<?= htmlspecialchars($user['birthdate']); ?>" />
        </div>

        <!-- Gender Field -->
        <div class="mb-6">
            <label class="block text-lg font-medium text-gray-700 mb-2" for="gender">Jenis Kelamin</label>
            <select class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" id="gender" name="gender">
                <option value="male" <?= $user['gender'] == 'male' ? 'selected' : ''; ?>>Laki-laki</option>
                <option value="female" <?= $user['gender'] == 'female' ? 'selected' : ''; ?>>Perempuan</option>
            </select>
        </div>

        <!-- Email Field -->
        <div class="mb-6">
            <label class="block text-lg font-medium text-gray-700 mb-2" for="email">Email</label>
            <input class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" name="email" placeholder="Masukkan email Anda" type="email" value="<?= htmlspecialchars($user['email']); ?>" />
        </div>

        <!-- Phone Field -->
        <div class="mb-6">
            <label class="block text-lg font-medium text-gray-700 mb-2" for="phone">Nomor Telepon</label>
            <input class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" id="phone" name="phone" placeholder="Masukkan nomor telepon Anda" type="text" value="<?= htmlspecialchars($user['phone']); ?>" />
        </div>

        <!-- Profile Picture Field -->
        <div class="mb-6">
            <label class="block text-lg font-medium text-gray-700 mb-2" for="profile_picture_input">Gambar Profil</label>
            <input type="file" id="profile_picture_input" accept="image/*" onchange="openCropperModal(event)" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl mb-2">
            <div class="w-40 h-40 overflow-hidden rounded-full border-2 border-gray-300 shadow-lg">
                <img id="crop_preview" src="" alt="Preview" class="object-cover w-full h-full">
            </div>
            <input type="hidden" name="cropped_image" id="cropped_image">
        </div>

        <!-- Modal for Cropping -->
        <div id="cropperModal" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="relative max-w-full max-h-[90vh] p-6 bg-white rounded-xl shadow-lg overflow-hidden flex flex-col items-center justify-center">
                <img id="modal_crop_image" class="w-full h-auto max-h-[80vh] object-contain mb-4">
                <div class="absolute top-0 right-0 m-2">
                    <button onclick="closeCropperModal()" class="bg-red-500 text-white px-6 py-3 rounded-full focus:outline-none">Close</button>
                </div>
                <div class="flex justify-between w-full mt-4 space-x-4">
                    <button onclick="closeCropperModal()" class="bg-gray-500 text-white px-6 py-3 rounded-full focus:outline-none">Cancel</button>
                    <button onclick="cropImage(event)" class="bg-blue-500 text-white px-6 py-3 rounded-full focus:outline-none">Crop</button>
                </div>
            </div>
        </div>

        <button class="w-full bg-blue-600 text-white text-lg font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition-all focus:outline-none" type="button" onclick="openConfirmationModal()">Save Changes</button>
    </form>

    <!-- Confirmation Modal -->
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" id="confirmationModal">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Konfirmasi</h2>
            <p class="text-lg text-gray-600 mb-6">Apakah Anda yakin ingin menyimpan perubahan?</p>
            <div class="flex justify-between space-x-4">
                <button class="bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 focus:outline-none" onclick="closeConfirmationModal()">Batal</button>
                <button class="bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none" onclick="saveChanges()">Ya, Simpan</button>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="fixed top-4 right-4 bg-green-600 text-white py-3 px-6 rounded-lg shadow-lg hidden" id="notification">
        Biodata telah diedit.
    </div>
</div>



    <footer class="bg-gray-800 text-white py-8 mt-auto">
        <div class="px-4 mx-auto max-w-screen-xl">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div>
                    <h2 class="text-2xl font-bold">SiBantu</h2>
                    <p class="mt-4 text-sm">Mitra andalan Anda untuk layanan sehari-hari. Hubungi kami kapan saja, di mana saja.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Quick Links</h3>
                    <nav class="mt-4">
                        <ul>
                            <li><a href="User_dashboard.php" class="text-gray-400 hover:text-white">Dashboard</a></li>
                            <li><a href="profile.php" class="text-gray-400 hover:text-white">Profile</a></li>
                            <li><a href="logout.php" class="text-gray-400 hover:text-white">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </footer>

    <script>

    function openConfirmationModal() {
        document.getElementById('confirmationModal').classList.remove('hidden');
    }

    function closeConfirmationModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
    }

    function showError(message) {
        const errorEl = document.getElementById('errorNotification');
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
        setTimeout(() => {
            errorEl.classList.add('hidden');
        }, 3000);
    }
    let cropper;
    let imageFile;

    // Open the Cropper Modal when a file is selected
    function openCropperModal(event) {
        imageFile = event.target.files[0];
        
        if (imageFile) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageUrl = e.target.result;
                // Show modal and preview image
                document.getElementById('cropperModal').classList.remove('hidden');
                const imageElement = document.getElementById('modal_crop_image');
                imageElement.src = imageUrl;

                // Initialize the cropper
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(imageElement, {
                    aspectRatio: 1, // Set aspect ratio for a square crop (profile picture)
                    viewMode: 2,    // Enable restriction of image cropping
                    autoCropArea: 1,
                    crop: function(event) {
                        // Update main preview in real-time as crop changes
                        updatePreview(event);
                    }
                });
            };
            reader.readAsDataURL(imageFile);
        }
    }

    // Update preview in real-time as crop changes
    function updatePreview(event) {
        const croppedCanvas = cropper.getCroppedCanvas();
        const croppedImageDataUrl = croppedCanvas.toDataURL();
        
        // Update main preview image (outside modal)
        document.getElementById('crop_preview').src = croppedImageDataUrl;
        
        // Update modal preview image (inside modal)
        document.getElementById('modal_crop_image').src = croppedImageDataUrl;
    }

    // Close the cropper modal
    function closeCropperModal() {
        document.getElementById('cropperModal').classList.add('hidden');
        if (cropper) {
            cropper.destroy(); // Clean up cropper instance
        }
    }

    // Crop and preview the image
    function cropImage(event) {
        event.preventDefault(); // Prevent default form submission or page reload
        
        // Get the cropped image from the cropper
        const croppedCanvas = cropper.getCroppedCanvas();
        const croppedImageDataUrl = croppedCanvas.toDataURL();
        
        // Show cropped image in the preview
        document.getElementById('crop_preview').src = croppedImageDataUrl;
        
        // Show cropped image in the modal preview (same as the crop preview)
        document.getElementById('modal_crop_image').src = croppedImageDataUrl;
        
        // Store cropped image data in the hidden input (base64 format)
        document.getElementById('cropped_image').value = croppedImageDataUrl;
        
        // Close the modal
        closeCropperModal();
    }



    function saveChanges() {
        const form = document.getElementById('editBiodataForm');
        const name = document.getElementById('name').value.trim();
        const birthdate = document.getElementById('birthdate').value;
        const gender = document.getElementById('gender').value;
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();

        if (!name || !birthdate || !gender || !email || !phone) {
            document.getElementById('errorNotification').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('errorNotification').classList.add('hidden');
            }, 3000);
            return;
        }

        const formData = new FormData(form);

        fetch('../database/save_biodata.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  document.getElementById('notification').classList.remove('hidden');
                  setTimeout(() => {
                      document.getElementById('notification').classList.add('hidden');
                  }, 3000);
              } else {
                  alert('Gagal menyimpan: ' + (data.message || 'Unknown error.'));
              }
          }).catch(error => {
              console.error('Error:', error);
              alert('Terjadi kesalahan saat menyimpan data.');
          });

        closeConfirmationModal();
    }
    </script>
    
</body>

</html>