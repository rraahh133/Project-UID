<?php
require './database/service_functions.php';
$kategori = isset($_POST['kategori']) ? $_POST['kategori'] : 'all';
$services = getFilteredServices($conn, $kategori);

$user = getUserData($conn);
$activeCategory = $kategori ?? 'all';
function buttonClass($category, $activeCategory) {
    return $category === $activeCategory
        ? 'px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700'
        : 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300';
}
$dashboardLink = './User/user_dashboard.php';
if (($user['usertype'] ?? '') === 'seller') {
    $dashboardLink = './Seller/provider-dashboard.php';
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Layanan - SiBantu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
    <style>
        .service-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Header -->
    <?php include './header.php'; ?>

    <section class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Jasa yang Kami Jual</h2>
            <p class="text-lg md:text-xl">Temukan berbagai layanan terbaik dari penyedia terpercaya di platform SiBantu</p>
        </div>
    </section>  

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Katalog Layanan</h1>
        
        <!-- Filter Section -->

        <form method="POST" id="filterForm">
            <input type="hidden" name="kategori" id="kategoriInput" value="<?= htmlspecialchars($activeCategory) ?>">
            <div class="mb-8">
                <div class="flex flex-wrap gap-4">
                    <button type="button" onclick="setCategory('all')" class="<?= buttonClass('all', $activeCategory) ?>">Semua</button>
                    <button type="button" onclick="setCategory('Desain')" class="<?= buttonClass('Desain', $activeCategory) ?>">Desain</button>
                    <button type="button" onclick="setCategory('Fotografi')" class="<?= buttonClass('Fotografi', $activeCategory) ?>">Fotografi</button>
                    <button type="button" onclick="setCategory('Teknologi')" class="<?= buttonClass('Teknologi', $activeCategory) ?>">Teknologi</button>
                    <button type="button" onclick="setCategory('Kebersihan')" class="<?= buttonClass('Kebersihan', $activeCategory) ?>">Kebersihan</button>
                    <button type="button" onclick="setCategory('Other')" class="<?= buttonClass('Other', $activeCategory) ?>">Other</button>
                </div>
            </div>
        </form>


        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($services as $service): ?>

                <?php
                    // Fetch average rating service, Filter Null + 0 Rating, count completed orders
                    $serviceId = $service['service_id'];
                    $query = "SELECT AVG(rating) AS avg_rating 
                            FROM orders 
                            WHERE service_id = $serviceId 
                            AND rating IS NOT NULL 
                            AND rating >= 1 
                            AND status = 'completed'";
                    $result = mysqli_query($conn, $query);
                    $ratingData = mysqli_fetch_assoc($result);
                    $avgRating = round($ratingData['avg_rating']);
                ?>

                <div class="bg-white rounded-xl shadow-md service-card overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2"></div>
                    <img src="<?= htmlspecialchars($service['service_image']) ?>" alt="<?= htmlspecialchars($service['service_name']) ?>" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($service['service_name']) ?></h3>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm"><?= htmlspecialchars($service['service_type']) ?></span>
                        </div>
                        
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $avgRating): ?>
                                        <i class="fas fa-star"></i> <!-- Filled star -->
                                    <?php else: ?>
                                        <i class="far fa-star"></i> <!-- Empty star -->
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span class="ml-2 text-gray-600">
                                <?php if ($avgRating > 0): ?>
                                    (<?= number_format($ratingData['avg_rating'], 0) ?> / 5)
                                <?php else: ?>
                                    (No rating)
                                <?php endif; ?>
                            </span>
                        </div>

                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($service['service_description']) ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-blue-600">Rp <?= number_format($service['service_price'], 0, ',', '.') ?></span>
                            <form action="payment.php" method="POST" style="display: inline;">
                                <input type="hidden" name="service_id" value="<?= $service['service_id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $service['user_id'] ?>">
                                
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Order Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php require './User/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Filter Function kategori
    function setCategory(kategori) {
        document.getElementById('kategoriInput').value = kategori;
        document.getElementById('filterForm').submit();
    }

    // disable resubmit on refresh page
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</body>
</html> 