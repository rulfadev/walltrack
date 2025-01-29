<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Daftar Kategori</h2>
    <a href="<?= base_url('categories/create') ?>" class="btn btn-primary mb-3">Tambah Kategori</a>

    <?= view('layouts\elements\_message_block') ?>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Kategori</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($categories as $category): ?>
                <tr>
                    <th scope="row" class="align-middle"><?= $no++ ?></th>
                    <td class="align-middle"><?= $category['name'] ?></td>
                    <td><button class="btn btn-danger btn-delete" data-id="<?= $category['id']; ?>" data-toggle="modal"
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
                fetch('/categories/delete', {
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