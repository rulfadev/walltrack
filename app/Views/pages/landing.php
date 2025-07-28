<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('layouts/_header'); ?>
</head>

<body class="index-page">

    <?= $this->include('layouts/_navbar'); ?>

    <main class="main">

        <!-- Hero Section -->
        <?= $this->include('layouts/landingpage/_hero'); ?>
        <!-- End Hero Section -->

        <!-- About Section -->
        <?= $this->include('layouts/landingpage/_about'); ?>
        <!-- End About Section -->

        <!-- Services Section -->
        <?= $this->include('layouts/landingpage/_service'); ?>
        <!-- End Services Section -->

        <!-- Features Section -->
        <?= $this->include('layouts/landingpage/_feature'); ?>
        <!-- End Features Section -->

        <!-- Faq Section -->
        <?= $this->include('layouts/landingpage/_faq'); ?>
        <!-- End Faq Section -->

    </main>

    <footer id="footer" class="footer light-background">

        <div class="container">
            <div class="copyright text-center ">
                <p>© <span>Copyright</span> 2025 -<strong class="px-1 sitename"><span
                            style="color: var(--accent-color);">Wall</span>Track</strong>| <span>All
                        Rights
                        Reserved</span></p>
            </div>
            <div class="social-links d-flex justify-content-center">
                <a href=""><i class="bi bi-twitter-x"></i></a>
                <a href=""><i class="bi bi-facebook"></i></a>
                <a href=""><i class="bi bi-instagram"></i></a>
                <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
            <div class="credits">
                Developed by <a href="http://rulfadev.my.id">RulfaDev</a>
            </div>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/aos/aos.js'); ?>"></script>
    <script src="<?= base_url('assets/glightbox/js/glightbox.min.js'); ?>"></script>

    <!-- Main JS File -->
    <script src="<?= base_url('assets/js/main.js'); ?>"></script>

</body>

</html>