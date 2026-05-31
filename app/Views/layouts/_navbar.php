<?php
$currentUrl = trim(uri_string(), '/');

$isActive = static function (string $path) use ($currentUrl): string {
    $path = trim($path, '/');

    if ($path === '') {
        return $currentUrl === '' ? 'active' : '';
    }

    return str_starts_with($currentUrl, $path) ? 'active' : '';
};

$isGuest = !session('isLoggedIn');
?>

<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="<?= base_url(); ?>" class="logo d-flex align-items-center">
            <h1 class="sitename"><span>Wall</span>Track</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <?php if ($isGuest): ?>
                    <li>
                        <a href="<?= base_url('#hero'); ?>" class="<?= $currentUrl === '' ? 'active' : ''; ?>">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('#about'); ?>">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('#services'); ?>">
                            Services
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('#features'); ?>">
                            Features
                        </a>
                    </li>

                    <?php if ($currentUrl === 'login'): ?>
                        <li>
                            <a class="btn-login" href="<?= base_url('signup'); ?>">
                                Sign Up
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a class="btn-login" href="<?= base_url('login'); ?>">
                                Login
                            </a>
                        </li>
                    <?php endif; ?>

                <?php else: ?>
                    <li>
                        <a href="<?= base_url('dashboard'); ?>" class="<?= $isActive('dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('transactions'); ?>" class="<?= $isActive('transactions'); ?>">
                            Transaksi
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('wallets'); ?>" class="<?= $isActive('wallets'); ?>">
                            Wallet
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('categories'); ?>" class="<?= $isActive('categories'); ?>">
                            Kategori
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('reports'); ?>" class="<?= $isActive('reports'); ?>">
                            Laporan
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('budgets'); ?>" class="<?= $isActive('budgets'); ?>">
                            Budget
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('profile'); ?>" class="<?= $isActive('profile'); ?>">
                            Profil
                        </a>
                    </li>

                    <li>
                        <a class="btn-login" href="<?= base_url('logout'); ?>">
                            Logout
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
    </div>
</header>