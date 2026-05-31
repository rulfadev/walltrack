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
];
?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Kategori</h2>
            <p class="text-muted mb-0">
                Kelola kategori pemasukan dan pengeluaran dengan ikon serta warna.
            </p>
        </div>

        <a href="<?= base_url('/categories/create'); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Kategori
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

    <form method="get" action="<?= base_url('/categories'); ?>" class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Tipe Kategori</label>
                    <select name="type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="income" <?= ($filters['type'] ?? '') === 'income' ? 'selected' : ''; ?>>
                            Pemasukan
                        </option>
                        <option value="expense" <?= ($filters['type'] ?? '') === 'expense' ? 'selected' : ''; ?>>
                            Pengeluaran
                        </option>
                    </select>
                </div>

                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>
                </div>

                <div class="col-md-2 d-grid">
                    <a href="<?= base_url('/categories'); ?>" class="btn btn-outline-secondary">
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
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Icon</th>
                            <th>Warna</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $index => $category): ?>
                                <?php
                                $type = $typeLabels[$category['type']] ?? [
                                    'label' => ucfirst((string) $category['type']),
                                    'class' => 'secondary',
                                    'icon' => 'bi-dot',
                                ];
                                ?>

                                <tr id="category-row-<?= esc($category['id']); ?>">
                                    <td><?= esc($index + 1); ?></td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span
                                                class="d-inline-flex align-items-center justify-content-center rounded-circle text-white"
                                                style="width: 34px; height: 34px; background: <?= esc($category['color'] ?: '#134686'); ?>;">
                                                <i class="bi <?= esc($category['icon'] ?: 'bi-tag'); ?>"></i>
                                            </span>

                                            <span class="fw-semibold">
                                                <?= esc($category['name']); ?>
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?= esc($type['class']); ?>">
                                            <i class="bi <?= esc($type['icon']); ?> me-1"></i>
                                            <?= esc($type['label']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <code><?= esc($category['icon'] ?: 'bi-tag'); ?></code>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle border"
                                                style="width: 22px; height: 22px; background: <?= esc($category['color'] ?: '#134686'); ?>;"></span>
                                            <code><?= esc($category['color'] ?: '#134686'); ?></code>
                                        </div>
                                    </td>

                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="<?= base_url('/categories/edit/' . $category['id']); ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="deleteCategory(<?= esc($category['id']); ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-tags fs-1 d-block mb-2"></i>
                                        Belum ada kategori.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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

    function deleteCategory(id) {
        if (!confirm('Yakin ingin menghapus kategori ini? Kategori yang sudah dipakai transaksi tidak bisa dihapus.')) {
            return;
        }

        fetch('<?= base_url('/categories/delete'); ?>', {
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
                    throw data || { message: 'Gagal menghapus kategori.' };
                }

                return data;
            })
            .then(data => {
                updateCsrfToken(data.csrfHash);

                if (data.success) {
                    const row = document.getElementById('category-row-' + id);

                    if (row) {
                        row.remove();
                    } else {
                        location.reload();
                    }

                    return;
                }

                alert(data.message || 'Kategori gagal dihapus.');
            })
            .catch(error => {
                alert(error.message || 'Terjadi kesalahan saat menghapus kategori.');
            });
    }
</script>

<?= $this->endSection(); ?>