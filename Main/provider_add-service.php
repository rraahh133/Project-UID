<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiBantu - Tambah Pekerjaan Baru</title>
    <link rel="stylesheet" href="css/provide.css">
    <script src="js/provider.js" defer></script>
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
            </nav>
    </header>
    <main class="main">
        <section class="hero">
            <h1>Tambah Pekerjaan Baru</h1>
            <p>Input pekerjaan baru yang ingin Anda tawarkan.</p>
        </section>
        <section class="crud">
            <h2>Manajemen Layanan</h2>
            <form id="service-form">
                <input type="text" id="service-name" placeholder="Nama Layanan" required>
                <button type="submit">Tambah Layanan</button>
            </form>
            <table id="service-table">
                <thead>
                    <tr>
                        <th>Nama Layanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data layanan akan ditambahkan di sini -->
                </tbody>
            </table>
        </section>
    </main>
    <footer class="footer">
        <p>&copy; 2024 Every.Co - Semua Hak Dilindungi</p>
    </footer>
</body>
</html>