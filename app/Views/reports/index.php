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

$query = $_GET ?? [];

$csvUrl = base_url('/reports/export/csv') . (!empty($query) ? '?' . http_build_query($query) : '');
$excelUrl = base_url('/reports/export/excel') . (!empty($query) ? '?' . http_build_query($query) : '');
?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Laporan Keuangan</h2>
            <p class="text-muted mb-0">
                Analisis pemasukan, pengeluaran, saldo bersih, transfer, dan transaksi berdasarkan filter.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="<?= esc($csvUrl); ?>" class="btn btn-outline-primary">
                <i class="bi bi-filetype-csv me-1"></i>
                Export CSV
            </a>

            <a href="<?= esc($excelUrl); ?>" class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i>
                Export Excel
            </a>
        </div>
    </div>

    <form method="get" action="<?= base_url('/reports'); ?>" class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control"
                        value="<?= esc($filters['start_date'] ?? date('Y-m-01')); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control"
                        value="<?= esc($filters['end_date'] ?? date('Y-m-d')); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Wallet</label>
                    <select name="wallet_id" class="form-select">
                        <option value="">Semua Wallet</option>
                        <?php foreach ($wallets as $wallet): ?>
                            <option value="<?= esc($wallet['id']); ?>" <?= ($filters['wallet_id'] ?? '') == $wallet['id'] ? 'selected' : ''; ?>
                                >
                                <?= esc($wallet['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Jenis</label>
                    <select name="type" id="typeFilter" class="form-select">
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
                    <select name="category_id" id="categoryFilter" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category['id']); ?>" data-type="<?= esc($category['type']); ?>"
                                <?= ($filters['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>
                                >
                                <?= esc($category['name']); ?>
                                —
                                <?= $category['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran'; ?>
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
                    <a href="<?= base_url('/reports'); ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Pemasukan</p>
                            <h4 class="fw-bold text-success mb-0">
                                Rp
                                <?= number_format((float) $summary['total_income'], 0, ',', '.'); ?>
                            </h4>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                    </div>
                    <small class="text-muted">Sesuai periode filter</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Pengeluaran</p>
                            <h4 class="fw-bold text-danger mb-0">
                                Rp
                                <?= number_format((float) $summary['total_expense'], 0, ',', '.'); ?>
                            </h4>
                        </div>
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                    </div>
                    <small class="text-muted">Sesuai periode filter</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Saldo Bersih</p>
                            <h4
                                class="fw-bold mb-0 <?= (float) $summary['net_balance'] >= 0 ? 'text-primary' : 'text-danger'; ?>">
                                Rp
                                <?= number_format((float) $summary['net_balance'], 0, ',', '.'); ?>
                            </h4>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                    <small class="text-muted">Pemasukan - Pengeluaran</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Transfer</p>
                            <h4 class="fw-bold text-primary mb-0">
                                Rp
                                <?= number_format((float) $summary['total_transfer'], 0, ',', '.'); ?>
                            </h4>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        <?= number_format((int) $summary['total_transactions'], 0, ',', '.'); ?> transaksi
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Detail Transaksi</h5>
                    <p class="text-muted mb-0">
                        Periode
                        <?= esc($filters['start_date']); ?> s/d
                        <?= esc($filters['end_date']); ?>
                    </p>
                </div>

                <span class="badge bg-light text-dark">
                    <?= number_format(count($transactions), 0, ',', '.'); ?> data tampil
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Wallet</th>
                            <th>Kategori / Tujuan</th>
                            <th>Nominal</th>
                            <th>Catatan</th>
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

                                <tr>
                                    <td>
                                        <?= esc($index + 1); ?>
                                    </td>

                                    <td>
                                        <?= esc(date('d M Y', strtotime($transaction['transaction_date']))); ?>
                                    </td>

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
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if (!empty($transaction['category_color'])): ?>
                                                    <span
                                                        class="d-inline-flex align-items-center justify-content-center rounded-circle text-white"
                                                        style="width: 28px; height: 28px; background: <?= esc($transaction['category_color']); ?>;">
                                                        <i class="bi <?= esc($transaction['category_icon'] ?: 'bi-tag'); ?>"></i>
                                                    </span>
                                                <?php endif; ?>

                                                <span>
                                                    <?= esc($transaction['category_name'] ?? '-'); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td class="fw-semibold">
                                        Rp
                                        <?= number_format((float) $transaction['amount'], 0, ',', '.'); ?>
                                    </td>

                                    <td>
                                        <?= esc($transaction['description'] ?: '-'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>
                                        Tidak ada transaksi pada filter ini.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($transactions)): ?>
                <div class="alert alert-info mb-0">
                    Export CSV/Excel akan mengikuti filter yang sedang aktif.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const typeFilter = document.getElementById('typeFilter');
    const categoryFilter = document.getElementById('categoryFilter');

    function filterCategoryByType() {
        const selectedType = typeFilter.value;
        let selectedIsValid = true;

        Array.from(categoryFilter.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const categoryType = option.getAttribute('data-type');

            if (!selectedType || selectedType === 'transfer') {
                option.hidden = selectedType === 'transfer';
            } else {
                option.hidden = categoryType !== selectedType;
            }

            if (option.selected && option.hidden) {
                selectedIsValid = false;
            }
        });

        if (!selectedIsValid) {
            categoryFilter.value = '';
        }

        if (selectedType === 'transfer') {
            categoryFilter.value = '';
            categoryFilter.disabled = true;
        } else {
            categoryFilter.disabled = false;
        }
    }

    typeFilter.addEventListener('change', filterCategoryByType);
    filterCategoryByType();
</script>

<?= $this->endSection(); ?>