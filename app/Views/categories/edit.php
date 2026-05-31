<?= $this->extend('/layout'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit Kategori</h2>
            <p class="text-muted mb-0">
                Perbarui nama, tipe, ikon, dan warna kategori.
            </p>
        </div>

        <a href="<?= base_url('/categories'); ?>" class="btn btn-outline-secondary">
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

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="<?= base_url('/categories/update/' . $category['id']); ?>" method="post">
                        <?= csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="name" id="categoryName" class="form-control"
                                value="<?= esc(old('name', $category['name'])); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipe Kategori</label>
                            <select name="type" id="categoryType" class="form-select" required>
                                <option value="expense" <?= old('type', $category['type']) === 'expense' ? 'selected' : ''; ?>>
                                    Pengeluaran
                                </option>
                                <option value="income" <?= old('type', $category['type']) === 'income' ? 'selected' : ''; ?>>
                                    Pemasukan
                                </option>
                            </select>
                            <div class="form-text">
                                Tipe kategori yang sudah dipakai transaksi tidak bisa diubah.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Icon Bootstrap</label>
                            <input type="text" name="icon" id="categoryIcon" class="form-control"
                                value="<?= esc(old('icon', $category['icon'] ?: 'bi-tag')); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Warna</label>
                            <input type="color" name="color" id="categoryColor" class="form-control form-control-color"
                                value="<?= esc(old('color', $category['color'] ?: '#134686')); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Preview</h5>

                    <div class="d-flex align-items-center gap-3">
                        <span id="previewCircle"
                            class="d-inline-flex align-items-center justify-content-center rounded-circle text-white"
                            style="width: 52px; height: 52px; background: <?= esc($category['color'] ?: '#134686'); ?>;">
                            <i id="previewIcon" class="bi <?= esc($category['icon'] ?: 'bi-tag'); ?> fs-4"></i>
                        </span>

                        <div>
                            <h6 class="fw-bold mb-1" id="previewName">
                                <?= esc($category['name']); ?>
                            </h6>
                            <span class="badge" id="previewType"></span>
                        </div>
                    </div>

                    <hr>

                    <p class="text-muted mb-0">
                        Warna dan ikon ini akan membantu kategori lebih mudah dikenali di transaksi dan laporan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const categoryName = document.getElementById('categoryName');
    const categoryType = document.getElementById('categoryType');
    const categoryIcon = document.getElementById('categoryIcon');
    const categoryColor = document.getElementById('categoryColor');

    const previewCircle = document.getElementById('previewCircle');
    const previewIcon = document.getElementById('previewIcon');
    const previewName = document.getElementById('previewName');
    const previewType = document.getElementById('previewType');

    function updatePreview() {
        const name = categoryName.value || 'Nama Kategori';
        const type = categoryType.value;
        const icon = categoryIcon.value || 'bi-tag';
        const color = categoryColor.value || '#134686';

        previewName.textContent = name;
        previewCircle.style.background = color;

        previewIcon.className = 'bi ' + icon + ' fs-4';

        if (type === 'income') {
            previewType.textContent = 'Pemasukan';
            previewType.className = 'badge bg-success';
        } else {
            previewType.textContent = 'Pengeluaran';
            previewType.className = 'badge bg-danger';
        }
    }

    [categoryName, categoryType, categoryIcon, categoryColor].forEach(element => {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    updatePreview();
</script>

<?= $this->endSection(); ?>