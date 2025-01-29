<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="/">Walltrack</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
        aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Beranda</a>
            </li>
            <?php if (session('isLoggedIn') == true): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/transactions">Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/categories">Kategori</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <?php if (session('isLoggedIn') == true): ?>
                    <a class="nav-link" href="/logout">Keluar</a>
                <?php else: ?>
                    <a class="nav-link" href="/login">Masuk</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</nav>