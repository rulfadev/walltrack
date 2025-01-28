<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">
                <h2 class="card-header">Halaman Login</h2>
                <div class="card-body">

                    <?= view('layouts\elements\_message_block') ?>

                    <form action="<?= url_to('login') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email"
                                class="form-control <?php if (session('errors.email')): ?>is-invalid<?php endif ?>"
                                name="email" placeholder="Email" value="<?= old('email') ?>">
                            <div class="invalid-feedback">
                                <?= ucfirst(session('errors.email')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Kata Sandi</label>
                            <input type="password" name="password"
                                class="form-control  <?php if (session('errors.password')): ?>is-invalid<?php endif ?>"
                                placeholder="Kata Sandi">
                            <div class="invalid-feedback">
                                <?= ucfirst(session('errors.password')) ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                    </form>

                    <hr>
                    <p>Apakah anda belum mempunyai akun? <a href="<?= url_to('register') ?>">buat serakang</a>.</p>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>