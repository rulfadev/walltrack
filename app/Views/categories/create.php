<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Tambah Kategori</h2>

    <form action="/categories/store" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Nama Kategori</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="/categories" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>