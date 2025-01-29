<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Daftar Transaksi</h2>
    <a href="<?= base_url('transactions/create') ?>" class="btn btn-primary mb-3">Tambah Transaksi</a>

    <?= view('layouts\elements\_message_block') ?>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kategori</th>
                <th scope="col">Debit</th>
                <th scope="col">Kredit</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Deskripsi</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($transactions as $transaction): ?>
                <tr>
                    <th scope="row" class="align-middle"><?= $no++ ?></th>
                    <td class="align-middle"><?= $transaction['category_name'] ?></td>
                    <td class="align-middle">
                        <span class="badge <?= ($transaction['type'] == 'debit') ? 'badge-success' : 'badge-secondary' ?>">
                            <?= ($transaction['type'] == 'debit') ? ucfirst($transaction['type']) : '-' ?>
                        </span>
                    </td>
                    <td class="align-middle">
                        <span class="badge <?= ($transaction['type'] == 'kredit') ? 'badge-danger' : 'badge-secondary' ?>">
                            <?= ($transaction['type'] == 'kredit') ? ucfirst($transaction['type']) : '-' ?>
                        </span>
                    </td>
                    <td class="align-middle">Rp. <?= number_format($transaction['amount'], 2, ',', '.') ?></td>
                    <td class="align-middle"><?= $transaction['transaction_date'] ?></td>
                    <td class="align-middle"><?= $transaction['description'] ?></td>
                    <td><button class="btn btn-danger btn-delete" data-id="<?= $transaction['id']; ?>" data-toggle="modal"
                            data-target="#deleteModal">Hapus</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= view('layouts\elements\_modal') ?>

<script>
    let deleteId = null; // Variabel untuk menyimpan ID transaksi yang akan dihapus
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                deleteId = this.getAttribute("data-id"); // Simpan ID transaksi
            });
        });

        document.getElementById('confirmDelete').addEventListener('click', function () {
            if (deleteId) {
                fetch('/transactions/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ id: deleteId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            $('#deleteModal').modal('hide'); // Tutup modal
                            location.reload(); // Refresh halaman
                        } else {
                            alert("Gagal menghapus transaksi!");
                        }
                    });
            }
        });
    });
</script>
<?= $this->endSection() ?>