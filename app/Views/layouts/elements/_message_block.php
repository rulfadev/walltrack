<?php if (session()->has('success')): ?>
    <div class="alert alert-success" style="color: var(--accent-color); background-color: var(--light-color);">
        <?= session('success') ?>
    </div>
<?php endif ?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-danger" style="background-color: var(--light-color);">
        <?= session('error') ?>
    </div>
<?php endif ?>

<?php if (session()->has('errors')): ?>
    <ul class="alert alert-danger pl-5" style="background-color: var(--light-color); list-style-type: none;">
        <?php foreach (session('errors') as $error): ?>
            <li col="1"><?= $error ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>