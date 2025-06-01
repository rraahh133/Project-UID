<?php
// ambil directory saat ini + basename ambil folder name usertype
$currentDir = basename(dirname($_SERVER['PHP_SELF']));

if (($user['usertype'] ?? '') === 'seller') {
    $dashboardLink = $currentDir === 'Seller' ? 'provider-dashboard.php' : './Seller/provider-dashboard.php';
} else {
    $dashboardLink = $currentDir === 'User' ? 'user_dashboard.php' : './User/user_dashboard.php';
}
$indexLink = $currentDir === 'Seller' ? '../index.php' : ($currentDir === 'User' ? '../index.php' : './index.php');
$logoutLink = $currentDir === 'User' || $currentDir === 'Seller' ? '../logout.php' : 'logout.php';
$authLink = $currentDir === 'User' || $currentDir === 'Seller' ? '../auth.php' : 'auth.php';
?>

<header class="bg-white shadow-md">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Logo -->
        <a href="<?= htmlspecialchars($indexLink) ?>" class="text-xl md:text-2xl font-bold text-black mr-auto no-underline">SiBantu</a>

        <!-- Center Nav -->
        <!-- Right Nav -->
        <div class="hidden md:flex items-center gap-4">
            <?php if ($user): ?>
                <div id="user-menu-container" class="relative">
                    <button 
                        type="button"
                        class="flex items-center gap-2 text-gray-800 hover:text-blue-600 focus:outline-none"
                        id="user-menu-button"
                        aria-expanded="false"
                        aria-haspopup="true"
                        onclick="document.getElementById('user-dropdown').classList.toggle('hidden')"
                    >
                        <span><?= htmlspecialchars($user['username']) ?></span>
                        <img src="<?= $user['info_profile_picture'] ? 'data:image/jpeg;base64,' . $user['info_profile_picture'] : 'https://storage.googleapis.com/a1aa/image/cCYjTRgvAFZBA5oP1xaxRnauVzPZZiKo62ESgUGl9aVxeG7JA.jpg' ?>" 
                            alt="Profile Picture"
                            class="w-10 h-10 rounded-full border-2 border-white"
                        />
                        <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="user-dropdown" class="absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                            <a href="<?= htmlspecialchars($dashboardLink) ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">
                                Settings
                            </a>
                            <a href="<?= htmlspecialchars($logoutLink) ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="relative group">
                    <a href="<?= htmlspecialchars($authLink) ?>" class="text-gray-700 hover:text-blue-600">Masuk <span class="ml-1">&#8250;</span></a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Hamburger -->
        <div class="md:hidden">
            <button onclick="toggleMenu()" class="text-gray-700 text-2xl focus:outline-none">&#9776;</button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden flex flex-col gap-4 px-6 py-4 bg-white">
        <a href="#testimonial-section" class="text-gray-700 hover:text-blue-500">Testimonial</a>
        <a href="#explore-section" class="text-gray-700 hover:text-blue-500">Katalog</a>
        <?php if ($user): ?>
            <a href="./User/User_dashboard.php" class="text-gray-700 hover:text-blue-500">Dashboard</a>
            <a href="logout.php" class="text-gray-700 hover:text-blue-500">Logout</a>
        <?php else: ?>
            <div class="relative">
                <button onclick="document.getElementById('mobile-login-dropdown').classList.toggle('hidden')" class="text-gray-700 hover:text-blue-500 flex items-center">
                    Masuk <span class="ml-1">&#8250;</span>
                </button>
            </div>
        <?php endif; ?>
    </div>
</header>   