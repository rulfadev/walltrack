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
            <li class="nav-item">
                <?php if (session('isLoggedIn') == true): ?>
                    <a class="nav-link" href="/logout">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="/login">Login</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</nav>