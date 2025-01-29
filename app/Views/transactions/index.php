<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Daftar Transaksi</h2>
    <a href="/transactions/create" class="btn btn-primary mb-3">Tambah Transaksi</a>

    <?= view('layouts\elements\_message_block') ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $transaction['category_id'] ?></td>
                    <td><?= number_format($transaction['amount'], 2) ?></td>
                    <td><?= $transaction['transaction_date'] ?></td>
                    <td><?= $transaction['description'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>