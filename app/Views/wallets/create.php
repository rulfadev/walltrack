<?= $this->extend('/layout'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Tambah Wallet</h2>
            <p class="text-muted mb-0">Buat dompet, rekening bank, e-wallet, atau tabungan.</p>
        </div>
        <a href="<?= base_url('/wallets'); ?>" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= esc($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="<?= base_url('/wallets/store'); ?>" method="post">
                <?= csrf_field(); ?>

                <div class="mb-3">
                    <label class="form-label">Nama Wallet</label>
                    <input type="text" name="name" class="form-control" value="<?= old('name'); ?>" placeholder="Contoh: Cash, BCA, DANA" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Wallet</label>
                    <select name="type" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                        <option value="ewallet">E-Wallet</option>
                        <option value="saving">Tabungan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Saldo Awal</label>
                    <input type="number" name="initial_balance" class="form-control" min="0" step="0.01" value="<?= old('initial_balance') ?: 0; ?>">
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Wallet
                </button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>