<?= $this->extend('/layout'); ?>
<?= $this->section('content'); ?>
<style type="text/css">
    main {
        height: 70vh;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Walltrack</h1>
            <hr>
            <p>Merupakan sebuah aplikasi pencatatan keuangan sederhana.</p>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>