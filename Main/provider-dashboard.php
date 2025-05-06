<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiBantu - Profil Penyedia Jasa</title>
    <link rel="stylesheet" href="css/provide.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <img src="assets/logo.png" alt="SiBantu Logo" class="logo">
            <nav class="nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php">Logout</a></li>
                    <li><a href="provider-dashboard.php">Profil</a></li>
                    <li><a href="provider_transaction-history.php">Riwayat Transaksi</a></li>
                    <li><a href="provider_add-service.php">Input/Tambah Jasa</a></li>
                    <li><a href="provider_user-reviews.php">Review User</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main">
        <section class="hero">
            <h1>Profil Penyedia Jasa</h1>
            <p>Kelola informasi profil Anda dengan mudah.</p>
        </section>
        <section class="profile">
            <form id="profile-form" class="form-container">
                <div class="form-group">
                    <label for="provider-name">Nama Penyedia Jasa:</label>
                    <input type="text" id="provider-name" placeholder="Nama Anda" required>
                </div>
                <div class="form-group">
                    <label for="provider-email">Email:</label>
                    <input type="email" id="provider-email" placeholder="Email Anda" required>
                </div>
                <div class="form-group">
                    <label for="provider-phone">Nomor Telepon:</label>
                    <input type="tel" id="provider-phone" placeholder="Nomor Telepon Anda" required>
                </div>
                <div class="form-group">
                    <label for="provider-address">Alamat:</label>
                    <input type="text" id="provider-address" placeholder="Alamat Anda" required>
                </div>
                <div class="form-group">
                    <label for="provider-description">Deskripsi:</label>
                    <textarea id="provider-description" placeholder="Deskripsi singkat tentang Anda" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Simpan Profil</button>
            </form>
        </section>
        <section class="profile-summary">
            <h2>Ringkasan Profil</h2>
            <div class="summary-group">
                <p>Nama: <span id="summary-name">Nama Penyedia Jasa</span></p>
                <p>Email: <span id="summary-email">Email Penyedia Jasa</span></p>
                <p>Nomor Telepon: <span id="summary-phone">Nomor Telepon Penyedia Jasa</span></p>
                <p>Alamat: <span id="summary-address">Alamat Penyedia Jasa</span></p>
                <p>Deskripsi: <span id="summary-description">Deskripsi singkat tentang Anda</span></p>
            </div>
        </section>
    </main>
    <footer class="footer">
        <p>&copy; 2024 Every.Co - Semua Hak Dilindungi</p>
        </footer>
    <script>
        document.getElementById('profile-form').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('summary-name').innerText = document.getElementById('provider-name').value;
            document.getElementById('summary-email').innerText = document.getElementById('provider-email').value;
            document.getElementById('summary-phone').innerText = document.getElementById('provider-phone').value;
            document.getElementById('summary-address').innerText = document.getElementById('provider-address').value;
            document.getElementById('summary-description').innerText = document.getElementById('provider-description').value;
        });
    </script>

</body>
</html>