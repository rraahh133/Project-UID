<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Biodata</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </link>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-5 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Edit Biodata</h2>
            <button onclick="history.back()"
                class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2" href="User_dashboard.php"></i>Back
            </button>
        </div>
        <form>
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Nama</label>
                <input type="text" class="form-control w-full px-3 py-2 border rounded-lg" id="name"
                    placeholder="Masukkan nama Anda">
            </div>
            <div class="mb-4">
                <label for="birthdate" class="block text-gray-700 font-bold mb-2">Tanggal Lahir</label>
                <input type="date" class="form-control w-full px-3 py-2 border rounded-lg" id="birthdate">
            </div>
            <div class="mb-4">
                <label for="gender" class="block text-gray-700 font-bold mb-2">Jenis Kelamin</label>
                <select class="form-control w-full px-3 py-2 border rounded-lg" id="gender">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="male">Laki-laki</option>
                    <option value="female">Perempuan</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" class="form-control w-full px-3 py-2 border rounded-lg" id="email"
                    placeholder="Masukkan email Anda">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-bold mb-2">Nomor Telepon</label>
                <input type="text" class="form-control w-full px-3 py-2 border rounded-lg" id="phone"
                    placeholder="Masukkan nomor telepon Anda">
            </div>
            <button type="submit"
                class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700">Save
                Changes</button>
        </form>
    </div>
</body>

</html>