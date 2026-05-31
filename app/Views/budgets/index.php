<?= $this->extend('/layout'); ?>
<?= $this->section('content'); ?>

<?php
$months = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember',
];
?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Budget Bulanan</h2>
            <p class="text-muted mb-0">
                Pantau batas pengeluaran setiap kategori untuk periode
                <?= esc($monthLabel); ?>.
            </p>
        </div>

        <a href="<?= base_url('/budgets/create'); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Budget
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

    <form method="get" action="<?= base_url('/budgets'); ?>" class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select">
                        <?php foreach ($months as $number => $name): ?>
                            <option value="<?= esc($number); ?>" <?= (int) $filters['month'] === $number ? 'selected' : ''; ?>
                                >
                                <?= esc($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" class="form-control" min="2000" max="2100"
                        value="<?= esc($filters['year']); ?>">
                </div>

                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>
                </div>

                <div class="col-md-2 d-grid">
                    <a href="<?= base_url('/budgets'); ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Budget</p>
                    <h4 class="fw-bold mb-0">
                        Rp
                        <?= number_format((float) $summary['total_budget'], 0, ',', '.'); ?>
                    </h4>
                    <small class="text-muted">Semua kategori</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Sudah Terpakai</p>
                    <h4 class="fw-bold text-danger mb-0">
                        Rp
                        <?= number_format((float) $summary['total_spent'], 0, ',', '.'); ?>
                    </h4>
                    <small class="text-muted">Pengeluaran aktual</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Sisa Budget</p>
                    <h4
                        class="fw-bold <?= (float) $summary['total_remaining'] >= 0 ? 'text-success' : 'text-danger'; ?> mb-0">
                        Rp
                        <?= number_format((float) $summary['total_remaining'], 0, ',', '.'); ?>
                    </h4>
                    <small class="text-muted">Budget - Pengeluaran</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <?php if (!empty($budgets)): ?>
            <?php foreach ($budgets as $budget): ?>
                <div class="col-lg-6" id="budget-card-<?= esc($budget['id']); ?>">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="d-inline-flex align-items-center justify-content-center rounded-circle text-white"
                                        style="width: 46px; height: 46px; background: <?= esc($budget['category_color'] ?: '#134686'); ?>;">
                                        <i class="bi <?= esc($budget['category_icon'] ?: 'bi-tag'); ?> fs-5"></i>
                                    </span>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            <?= esc($budget['category_name']); ?>
                                        </h5>
                                        <span class="badge bg-<?= esc($budget['status']['class']); ?>">
                                            <?= esc($budget['status']['label']); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="d-inline-flex gap-1">
                                    <a href="<?= base_url('/budgets/edit/' . $budget['id']); ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteBudget(<?= esc($budget['id']); ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-4">
                                    <p class="text-muted mb-1">Budget</p>
                                    <div class="fw-bold">
                                        Rp
                                        <?= number_format((float) $budget['amount'], 0, ',', '.'); ?>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <p class="text-muted mb-1">Terpakai</p>
                                    <div class="fw-bold text-danger">
                                        Rp
                                        <?= number_format((float) $budget['spent'], 0, ',', '.'); ?>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <p class="text-muted mb-1">Sisa</p>
                                    <div
                                        class="fw-bold <?= (float) $budget['remaining'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                        Rp
                                        <?= number_format((float) $budget['remaining'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Progress</small>
                                <small class="fw-semibold">
                                    <?= esc($budget['raw_percentage']); ?>%
                                </small>
                            </div>

                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-<?= esc($budget['status']['class']); ?>" role="progressbar"
                                    style="width: <?= esc($budget['percentage']); ?>%;"
                                    aria-valuenow="<?= esc($budget['percentage']); ?>" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-piggy-bank fs-1 text-muted d-block mb-3"></i>
                        <h5 class="fw-bold">Belum ada budget</h5>
                        <p class="text-muted">
                            Tambahkan budget untuk kategori pengeluaran agar keuangan bulanan lebih terkontrol.
                        </p>
                        <a href="<?= base_url('/budgets/create'); ?>" class="btn btn-primary">
                            Tambah Budget
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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

    function deleteBudget(id) {
        if (!confirm('Yakin ingin menghapus budget ini?')) {
            return;
        }

        fetch('<?= base_url('/budgets/delete'); ?>', {
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
                    throw data || { message: 'Gagal menghapus budget.' };
                }

                return data;
            })
            .then(data => {
                updateCsrfToken(data.csrfHash);

                if (data.success) {
                    const card = document.getElementById('budget-card-' + id);

                    if (card) {
                        card.remove();
                    } else {
                        location.reload();
                    }

                    return;
                }

                alert(data.message || 'Budget gagal dihapus.');
            })
            .catch(error => {
                alert(error.message || 'Terjadi kesalahan saat menghapus budget.');
            });
    }
</script>

<?= $this->endSection(); ?>