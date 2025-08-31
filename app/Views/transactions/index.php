<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>
<div class="container my-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Transactions</h2>
            <p class="text-muted mb-0">Manage and track all your financial activities easily.</p>
        </div>
        <a href="<?= base_url('transactions/create') ?>" class="btn btn-success shadow-sm px-4">
            <i class="bi bi-plus-circle me-1"></i> Add Transaction
        </a>
    </div>

    <!-- Message Alert -->
    <?= view('layouts\elements\_message_block') ?>

    <!-- Card with Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>Category</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($transactions as $transaction): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $transaction['category_name'] ?></td>
                                <td class="text-center">
                                    <?php if ($transaction['type'] == 'debit'): ?>
                                        <span class="badge bg-success px-3 py-2">Debit</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($transaction['type'] == 'kredit'): ?>
                                        <span class="badge bg-danger px-3 py-2">Credit</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong>Rp. <?= number_format($transaction['amount'], 2, ',', '.') ?></strong></td>
                                <td><?= date('d M Y', strtotime($transaction['transaction_date'])) ?></td>
                                <td><?= $transaction['description'] ?></td>
                                <td class="text-center">
                                    <button class="btn btn-outline-danger btn-sm btn-delete"
                                        data-id="<?= $transaction['id']; ?>" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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