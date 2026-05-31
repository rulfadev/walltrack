<?= $this->extend('/layout'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Tambah Budget</h2>
            <p class="text-muted mb-0">
                Tentukan batas pengeluaran bulanan untuk kategori tertentu.
            </p>
        </div>

        <a href="<?= base_url('/budgets'); ?>" class="btn btn-outline-secondary">
            Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li>
                        <?= esc($error); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (!empty($categories)): ?>
                <form action="<?= base_url('/budgets/store'); ?>" method="post">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label">Kategori Pengeluaran</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= esc($category['id']); ?>" <?= old('category_id') == $category['id'] ? 'selected' : ''; ?>>
                                    <?= esc($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">
                            Budget hanya berlaku untuk kategori Pengeluaran.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Bulan</label>
                            <select name="month" class="form-select" required>
                                <?php foreach ($months as $number => $name): ?>
                                    <option value="<?= esc($number); ?>" <?= (int) old('month', date('n')) === $number ? 'selected' : ''; ?>>
                                        <?= esc($name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="year" class="form-control" min="2000" max="2100"
                                value="<?= esc(old('year', date('Y'))); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Nominal Budget</label>
                        <input type="number" name="amount" class="form-control" min="1" step="0.01"
                            value="<?= esc(old('amount')); ?>" placeholder="Contoh: 1500000" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Budget
                    </button>
                </form>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-tags fs-1 text-muted d-block mb-3"></i>
                    <h5 class="fw-bold">Belum ada kategori Pengeluaran</h5>
                    <p class="text-muted">
                        Buat kategori Pengeluaran terlebih dahulu sebelum membuat budget.
                    </p>
                    <a href="<?= base_url('/categories/create'); ?>" class="btn btn-primary">
                        Tambah Kategori
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>