<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="<?= base_url(); ?>" class="logo d-flex align-items-center">
            <h1 class="sitename"><span>Wall</span>Track</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="<?= base_url('#hero') ?>" class="active">Home</a></li>
                <li><a href="<?= base_url('#about') ?>">About</a></li>
                <li><a href="<?= base_url('#services') ?>">Services</a></li>
                <li><a href="<?= base_url('#features') ?>">Features</a></li>
                <?php if (!session('isLoggedIn')): ?>
                    <?php if (uri_string() === 'login'): ?>
                        <li><a class="btn-login" href="<?= base_url('signup') ?>">Sign Up</a></li>
                    <?php else: ?>
                        <li><a class="btn-login" href="<?= base_url('login') ?>">Login</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a class="btn-login" href="<?= base_url('logout') ?>">Logout</a></li>
                <?php endif; ?>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

    </div>
</header>