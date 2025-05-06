<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sibantu</title>
<link rel="stylesheet" href="css/index.css">
</head>
<body>
    
<section class="landing-page">

    <header>
        <div class="navbar">
            <a class="logo" href="index.php">SiBantu</a>
            <div class="nav-center">
                <a href="#testimonial-section">Testimonial</a>
                <a href="#explore-section">Katalog</a>
            </div>
            <div class="nav-right">
                <a href="login_page.php">Masuk</a>
            </div>
            <!-- Hamburger icon for mobile -->
            <div class="hamburger" onclick="toggleMenu()">
                &#9776;
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu">
            <a href="#testimonial-section">Testimonial</a>
            <a href="#explore-section">Katalog</a>
            <a href="login_page.php">Masuk</a>
        </div>
    </header>
    

    <section class="hero">
        <div class="hero-text">
            <h1>ANDA ORANG SIBUK?</h1>
            <p><a href="https://api.whatsapp.com/send/?phone=6282232371517&text&type=phone_number&app_absent=0">HUBUNGI KAMI UNTUK MEMBANTU ANDA</a></p>
            <span>Bantu Kepentingan Anda Dengan Lebih Mudah!</span>
        </div>
        <div class="hero-image">
            <img src="pict\Helper.jpg" alt="Helper">
        </div>
    </section>

    <section class="explore-section" id="explore-section">
    <div class="explore-content">
        <div class="explore-header">
        <nav class="explore-nav">
            <a href="#" class="explore-link">Jelajah</a>
            <a href="#" class="history-link">History</a>
        </nav>
        </div>
        <div class="explore-categories">
        <div class="category-list">
            <a href="#" class="category-item">Ojek</a>
            <a href="#" class="category-item">Cleaning Service</a>
            <a href="#" class="category-item">Kirim Barang</a>
            <a href="#" class="category-item">Beli Barang</a>
        </div>
        <a href="#" class="see-more">
            <span class="see-more-text">Lihat lebih banyak</span>
            <img loading="lazy" src="https://cdn.builder.io/api/v1/image/assets/TEMP/1def4b2a63c4fd9aba430c8cf3fa2518f89157a938e88c286f3ca441e61320bb?apiKey=adbf07bb3ee94c53a3493a3064ecc270&" class="see-more-icon" alt="See more icon" />
        </a>
        </div>
        <div class="item-grid">
        <article class="item-card">
            <img loading="lazy" src="pict\Pesan-Antar.jpg" class="item-image" alt="Pesan-Antar" />
            <h3 class="item-title">Pesan-Antar</h3>
            <p class="item-description">Kami menerima jasa pesan-antar</p>
            <div class="item-footer">
            <span class="item-price">Rp 15000</span>
            <button class="order-button">Pesan</button>
            </div>
        </article>
        <article class="item-card">
            <img loading="lazy" src="pict\Cleaner.jpg" class="item-image" alt="Cleaner" />
            <h3 class="item-title">Cleaning Service</h3>
            <p class="item-description">Kami menerima jasa bersih-bersih dan beres-beres rumah dll</p>
            <div class="item-footer">
            <span class="item-price">Rp 50000</span>
            <button class="order-button">Pesan</button>
            </div>
        </article>
        <article class="item-card">
            <img loading="lazy" src="pict\Barber.jpg" class="item-image" alt="Barber" />
            <h3 class="item-title">Cukur Rambut</h3>
            <p class="item-description">Kami menerima jasa cukur rambut panggilan</p>
            <div class="item-footer">
            <span class="item-price">Rp 25000</span>
            <button class="order-button">Pesan</button>
            </div>
        </article>
        </div>
    </div>
    </section>

    <section class="testimonial-section" id="testimonial-section">
    <div class="testimonial-content">
        <h2 class="testimonial-heading">Testimonial</h2>
        <div class="testimonial-grid">
        <article class="testimonial-card">
            <img loading="lazy" src="https://cdn.builder.io/api/v1/image/assets/TEMP/5147345f4012f2bac3e8746dd74868e881f6fffa8483f29436d54e586cf5e086?apiKey=adbf07bb3ee94c53a3493a3064ecc270&" class="testimonial-avatar" alt="User avatar" />
            <div class="testimonial-text">
            <h3 class="testimonial-name">K.P</h3>
            <p class="testimonial-comment">Jasa pesan-antar cepet dan ekonomis</p>
            </div>
        </article>
        <article class="testimonial-card">
            <img loading="lazy" src="https://cdn.builder.io/api/v1/image/assets/TEMP/abc0e7c23be209f54c657afc09390d275a39de73c2760cd7bd87e0a9faa3dd8e?apiKey=adbf07bb3ee94c53a3493a3064ecc270&" class="testimonial-avatar" alt="User avatar" />
            <div class="testimonial-text">
            <h3 class="testimonial-name">Iwan</h3>
            <p class="testimonial-comment">Platform penyedia jasa yang bagus</p>
            </div>
        </article>
        <article class="testimonial-card">
            <img loading="lazy" src="https://cdn.builder.io/api/v1/image/assets/TEMP/4c54bf216a2d13d36bc959090d30d38527622c1e829cc92223f448ffda61dcd9?apiKey=adbf07bb3ee94c53a3493a3064ecc270&" class="testimonial-avatar" alt="User avatar" />
            <div class="testimonial-text">
            <h3 class="testimonial-name">Udin</h3>
            <p class="testimonial-comment">Puji tuhan, jadi jarang nganggur sejak jadi mitra SiBantu</p>
            </div>
        </article>
    </div>
    </section>

    <footer class="footer">
    <div class="footer-content">
        <h2 class="footer-logo">SiBantu</h2>
        <nav class="footer-links">
        <div class="footer-column">
            <a href="#" class="footer-link">Katalog</a>
            <a href="#" class="footer-link">Kategori</a>
        </div>
        <div class="footer-column">
            <a href="faq.php" class="footer-link">FaQ</a>
            <a href="#" class="footer-link">About Us</a>
        </div>
        </nav>
    </div>
    </footer>

</section>

<script src="js/script.js"></script>

</body>
</html>