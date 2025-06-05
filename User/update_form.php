<?php
include('../database/service_functions.php'); // where getUserData() is defined

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$user = getUserData($conn, $user_id);

if (!$user) {
    header("Location: ../auth.php");
    exit;
}
$email_from_users = $user['user_email'] ?? null;
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

<body class="bg-gray-100 font-sans">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <?php require '../header.php'; ?>


        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-md">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-semibold text-gray-800">Edit Biodata</h2>
                    <a href="user_dashboard.php" class="inline-flex items-center text-gray-600 hover:text-gray-800 text-lg font-semibold py-2 px-4 rounded-lg bg-gray-100 hover:bg-gray-200 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                </div>

                <form id="editBiodataForm" enctype="multipart/form-data" method="POST" class="space-y-6">
                    <!-- Profile Picture Field -->
                    <div class="flex flex-col items-center mb-8">
                        <div class="w-48 h-48 overflow-hidden rounded-full border-4 border-gray-300 shadow-lg mb-4">
                            <img id="crop_preview" src="<?= $user['info_profile_picture'] ? 'data:image/jpeg;base64,' . $user['info_profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" alt="Preview" class="object-cover w-full h-full">
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <label class="block text-lg font-medium text-gray-700" for="info_profile_picture_input">
                                <span class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-all cursor-pointer">
                                    <i class="fas fa-camera mr-2"></i>Ubah Foto Profil
                                </span>
                            </label>
                            <input type="file" id="info_profile_picture_input" accept="image/*" onchange="openCropperModal(event)" class="hidden">
                            <p class="text-sm text-gray-500">Format yang didukung: JPG, PNG, GIF (Max. 2MB)</p>
                        </div>
                        <input type="hidden" name="cropped_image" id="cropped_image">
                    </div>

                    <!-- Name Field -->
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2" for="name">Nama</label>
                        <input class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500" id="name" name="name" placeholder="Masukkan nama Anda" type="text" value="<?= htmlspecialchars($user['info_name'] ?? ''); ?>" />
                    </div>

                    <!-- Birthdate Field -->
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2" for="birthdate">Tanggal Lahir</label>
                        <input class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500" id="birthdate" name="birthdate" type="date" value="<?= htmlspecialchars($user['info_birthdate'] ?? ''); ?>" />
                    </div>

                    <!-- Gender Field -->
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2" for="gender">Jenis Kelamin</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500" id="gender" name="gender">
                            <option value="male" <?= ($user['info_gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="female" <?= ($user['info_gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2" for="email">Email (Perhatian: Mengubah email di sini akan mengganti alamat email yang terdaftar)</label>
                        <input class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500" id="email" name="email" placeholder="Masukkan email Anda" type="email" value="<?= htmlspecialchars($email_from_users ?? '-') ?>" />
                    </div>

                    <!-- Phone Field -->
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2" for="phone">Nomor Telepon</label>
                        <input class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-500" id="phone" name="phone" placeholder="Masukkan nomor telepon Anda" type="text" value="<?= htmlspecialchars($user['info_phone'] ?? ''); ?>" />
                    </div>

                    <!-- Modal for Cropping & Live Preview -->
                    <div id="cropperModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden p-8 max-w-5xl w-full flex space-x-8">

                            <!-- Left panel: Live Preview -->
                            <div class="w-1/3 flex flex-col items-center border-r border-gray-200 pr-6">
                            <h3 class="text-lg font-semibold mb-6">Preview</h3>
                                <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-gray-300 mb-6">
                                    <img id="crop_preview_modal" 
                                    src="<?= $user['info_profile_picture'] ? 'data:image/jpeg;base64,' . $user['info_profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" 
                                    alt="Crop Preview" 
                                    class="object-cover w-full h-full" 
                                    />
                                </div>
                            </div>

                            <!-- Right panel: Cropper -->
                            <div class="w-2/3 flex flex-col items-center">
                                <h3 class="text-xl font-semibold mb-6">Edit Foto Profil</h3>
                                <img id="modal_crop_image" class="w-full h-auto max-h-[60vh] object-contain mb-6" src="" alt="To be cropped" />
                            
                                <div class="flex justify-between w-full mt-4 space-x-4">
                                    <button onclick="closeCropperModal()" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-all focus:outline-none">
                                    <i class="fas fa-times mr-1"></i>Cancel
                                    </button>
                                    <button onclick="cropImage(event)" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-all focus:outline-none">
                                    <i class="fas fa-crop mr-1"></i>Crop
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <button class="w-full bg-gray-800 text-white text-lg font-semibold py-3 px-6 rounded-lg hover:bg-gray-700 transition-all focus:outline-none" type="button" onclick="openConfirmationModal()">Simpan Perubahan</button>
                </form>

                <!-- Confirmation Modal -->
                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" id="confirmationModal">
                    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Konfirmasi</h2>
                        <p class="text-lg text-gray-600 mb-6">Apakah Anda yakin ingin menyimpan perubahan?</p>
                        <div class="flex justify-between space-x-4">
                            <button class="bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 focus:outline-none" onclick="closeConfirmationModal()">Batal</button>
                            <button class="bg-gray-800 text-white font-semibold py-3 px-6 rounded-lg hover:bg-gray-700 focus:outline-none" onclick="saveChanges()">Ya, Simpan</button>
                        </div>
                    </div>
                </div>

                <!-- Notification -->
                <div class="fixed top-4 right-4 bg-green-600 text-white py-3 px-6 rounded-lg shadow-lg hidden" id="notification">
                    Biodata telah diedit.
                </div>
            </div>
        </main>

        <!-- Footer -->
        <?php require 'footer.php'; ?>

    </div>

    <script>
    function openConfirmationModal() {
        document.getElementById('confirmationModal').classList.remove('hidden');
    }

    function closeConfirmationModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
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
                document.getElementById('cropperModal').classList.remove('hidden');
                const imageElement = document.getElementById('modal_crop_image');
                imageElement.src = imageUrl;

                // Initialize the cropper
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(imageElement, {
                    aspectRatio: 1,
                    viewMode: 2,   
                    autoCropArea: 1,
                    crop: function(event) {
                        updatePreview(event);
                    }
                });
            };
            reader.readAsDataURL(imageFile);
        }
    }

    function updatePreview(event) {
        const croppedCanvas = cropper.getCroppedCanvas();
        const croppedImageDataUrl = croppedCanvas.toDataURL();
        document.getElementById('crop_preview').src = croppedImageDataUrl;
        document.getElementById('modal_crop_image').src = croppedImageDataUrl;
        document.getElementById('crop_preview_modal').src = croppedImageDataUrl;
    }

    // Close the cropper modal
    function closeCropperModal() {
        document.getElementById('cropperModal').classList.add('hidden');
        if (cropper) {
            cropper.destroy(); // Clean up cropper instance
        }
    }

    function cropImage(event) {
        event.preventDefault(); 
        const croppedCanvas = cropper.getCroppedCanvas();
        const croppedImageDataUrl = croppedCanvas.toDataURL();
        document.getElementById('crop_preview').src = croppedImageDataUrl;
        document.getElementById('modal_crop_image').src = croppedImageDataUrl;
        document.getElementById('cropped_image').value = croppedImageDataUrl;
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
        fetch('../database/user-save_biodata.php', {
            method: 'POST',
            body: formData
        })
        .then(async response => {
            try {
                const data = await response.json();
                if (data.success) {
                    document.getElementById('notification').classList.remove('hidden');
                    setTimeout(() => {
                        document.getElementById('notification').classList.add('hidden');
                    }, 3000);
                } else {
                }
            } catch (e) {
                alert('Gagal menyimpan: ' + "Foto terlalu Besar, maksimal 2MB. Pastikan formatnya JPG, PNG, atau GIF.");
            }
        })
        
        closeConfirmationModal();
    }
    </script>
</body>

</html>