<?= $this->extend('/layout'); ?>
<?= $this->section('content'); ?>

<?php
$typeLabels = [
    'income' => [
        'label' => 'Pemasukan',
        'class' => 'success',
        'icon' => 'bi-arrow-down-circle',
    ],
    'expense' => [
        'label' => 'Pengeluaran',
        'class' => 'danger',
        'icon' => 'bi-arrow-up-circle',
    ],
    'transfer' => [
        'label' => 'Transfer',
        'class' => 'primary',
        'icon' => 'bi-arrow-left-right',
    ],
];
?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Transaksi</h2>
            <p class="text-muted mb-0">
                Kelola pemasukan, pengeluaran, dan transfer antar wallet.
            </p>
        </div>

        <a href="<?= base_url('/transactions/create'); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Transaksi
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')); ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')); ?>
        </div>
    <?php endif; ?>

    <form method="get" action="<?= base_url('/transactions'); ?>" class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control"
                        value="<?= esc($filters['start_date'] ?? ''); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control"
                        value="<?= esc($filters['end_date'] ?? ''); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Wallet</label>
                    <select name="wallet_id" class="form-select">
                        <option value="">Semua Wallet</option>
                        <?php foreach ($wallets as $wallet): ?>
                            <option value="<?= esc($wallet['id']); ?>" <?= ($filters['wallet_id'] ?? '') == $wallet['id'] ? 'selected' : ''; ?>>
                                <?= esc($wallet['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Jenis</label>
                    <select name="type" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="income" <?= ($filters['type'] ?? '') === 'income' ? 'selected' : ''; ?>>
                            Pemasukan
                        </option>
                        <option value="expense" <?= ($filters['type'] ?? '') === 'expense' ? 'selected' : ''; ?>>
                            Pengeluaran
                        </option>
                        <option value="transfer" <?= ($filters['type'] ?? '') === 'transfer' ? 'selected' : ''; ?>>
                            Transfer
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category['id']); ?>" <?= ($filters['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                <?= esc($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>
                </div>

                <div class="col-md-2 d-grid">
                    <a href="<?= base_url('/transactions'); ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Jenis</th>
                            <th>Wallet</th>
                            <th>Kategori / Tujuan</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                            <th>Catatan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transactions)): ?>
                            <?php foreach ($transactions as $index => $transaction): ?>
                                <?php
                                $type = $typeLabels[$transaction['type']] ?? [
                                    'label' => ucfirst((string) $transaction['type']),
                                    'class' => 'secondary',
                                    'icon' => 'bi-dot',
                                ];
                                ?>

                                <tr id="transaction-row-<?= esc($transaction['id']); ?>">
                                    <td><?= esc($index + 1); ?></td>

                                    <td>
                                        <span class="badge bg-<?= esc($type['class']); ?>">
                                            <i class="bi <?= esc($type['icon']); ?> me-1"></i>
                                            <?= esc($type['label']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= esc($transaction['wallet_name'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?php if ($transaction['type'] === 'transfer'): ?>
                                            <span class="text-muted">
                                                <?= esc($transaction['wallet_name'] ?? '-'); ?>
                                            </span>
                                            <i class="bi bi-arrow-right mx-1"></i>
                                            <strong>
                                                <?= esc($transaction['target_wallet_name'] ?? '-'); ?>
                                            </strong>
                                        <?php else: ?>
                                            <?= esc($transaction['category_name'] ?? '-'); ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="fw-semibold">
                                        Rp<?= number_format((float) $transaction['amount'], 0, ',', '.'); ?>
                                    </td>

                                    <td>
                                        <?= esc(date('d M Y', strtotime($transaction['transaction_date']))); ?>
                                    </td>

                                    <td>
                                        <?= esc($transaction['description'] ?: '-'); ?>
                                    </td>

                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="<?= base_url('/transactions/edit/' . $transaction['id']); ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="deleteTransaction(<?= esc($transaction['id']); ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                                        Belum ada transaksi.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (isset($pager)): ?>
                <div class="mt-3">
                    <?= $pager->links('transactions'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    let csrfTokenName = document.querySelector('meta[name="csrf-token-name"]').content;
    let csrfTokenHash = document.querySelector('meta[name="csrf-token-hash"]').content;

    function updateCsrfToken(newHash) {
        if (!newHash) {
            return;
        }

        csrfTokenHash = newHash;
        document
            .querySelector('meta[name="csrf-token-hash"]')
            .setAttribute('content', newHash);
    }

    function deleteTransaction(id) {
        if (!confirm('Yakin ingin menghapus transaksi ini? Saldo wallet akan dikembalikan sesuai transaksi.')) {
            return;
        }

        fetch('<?= base_url('/transactions/delete'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfTokenHash
            },
            body: JSON.stringify({
                id: id,
                [csrfTokenName]: csrfTokenHash
            })
        })
            .then(async response => {
                const data = await response.json().catch(() => null);

                if (!response.ok) {
                    throw data || {
                        message: 'Gagal menghapus transaksi.'
                    };
                }

                return data;
            })
            .then(data => {
                updateCsrfToken(data.csrfHash);

                if (data.success) {
                    const row = document.getElementById('transaction-row-' + id);

                    if (row) {
                        row.remove();
                    } else {
                        location.reload();
                    }

                    return;
                }

                alert(data.message || 'Transaksi gagal dihapus.');
            })
            .catch(error => {
                alert(error.message || 'Terjadi kesalahan saat menghapus transaksi.');
            });
    }
</script>

<?= $this->endSection(); ?>