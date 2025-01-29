<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">
                <h2 class="card-header">Halaman Daftar</h2>
                <div class="card-body">

                    <?= view('layouts\elements\_message_block') ?>

                    <form action="<?= url_to('register') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email"
                                class="form-control <?php if (session('errors.email')): ?>is-invalid<?php endif ?>"
                                name="email" aria-describedby="emailHelp" placeholder="Email"
                                value="<?= old('email') ?>">
                            <small id="emailHelp" class="form-text text-muted">Kami tidak akan membagikan data
                                pribadi anda kepada siapapun.</small>
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text"
                                class="form-control <?php if (session('errors.username')): ?>is-invalid<?php endif ?>"
                                name="username" placeholder="Username" value="<?= old('username') ?>">
                        </div>

                        <div class="form-group">
                            <label for="password">Kata Sandi</label>
                            <input type="password" name="password"
                                class="form-control <?php if (session('errors.password')): ?>is-invalid<?php endif ?>"
                                placeholder="Kata Sandi" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="pass_confirm">Ulangi Kata Sandi</label>
                            <input type="password" name="pass_confirm"
                                class="form-control <?php if (session('errors.pass_confirm')): ?>is-invalid<?php endif ?>"
                                placeholder="Ulangi Kata Sandi" autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                    </form>


                    <hr>

                    <p>Apakah anda sudah mempunyai akun? <a href="<?= url_to('login') ?>">masuk serakang</a>.</p>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>