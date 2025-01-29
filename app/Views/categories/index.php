<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Daftar Kategori</h2>
    <a href="/categories/create" class="btn btn-primary mb-3">Tambah Kategori</a>

    <?= view('layouts\elements\_message_block') ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($categories as $category): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $category['name'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>