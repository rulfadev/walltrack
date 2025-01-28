<?php if (session()->has('success')): ?>
    <div class="alert alert-success">
        <?= session('success') ?>
    </div>
<?php endif ?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-danger">
        <?= session('error') ?>
    </div>
<?php endif ?>

<?php if (session()->has('errors')): ?>
    <ul class="alert alert-danger pl-5">
        <?php foreach (session('errors') as $error): ?>
            <li col="1"><?= $error ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>