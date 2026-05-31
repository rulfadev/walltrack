<?= $this->extend('/layout'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit Wallet</h2>
            <p class="text-muted mb-0">
                Perbarui nama, jenis, warna, dan ikon wallet.
            </p>
        </div>

        <a href="<?= base_url('/wallets'); ?>" class="btn btn-outline-secondary">
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
                    <form action="<?= base_url('/wallets/update/' . $wallet['id']); ?>" method="post">
                        <?= csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label">Nama Wallet</label>
                            <input type="text" name="name" class="form-control"
                                value="<?= esc(old('name', $wallet['name'])); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Wallet</label>
                            <select name="type" class="form-select" required>
                                <option value="cash" <?= old('type', $wallet['type']) === 'cash' ? 'selected' : ''; ?>>
                                    Cash
                                </option>
                                <option value="bank" <?= old('type', $wallet['type']) === 'bank' ? 'selected' : ''; ?>>
                                    Bank
                                </option>
                                <option value="ewallet" <?= old('type', $wallet['type']) === 'ewallet' ? 'selected' : ''; ?>>
                                    E-Wallet
                                </option>
                                <option value="saving" <?= old('type', $wallet['type']) === 'saving' ? 'selected' : ''; ?>
                                    >
                                    Tabungan
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Warna</label>
                            <input type="color" name="color" class="form-control form-control-color"
                                value="<?= esc(old('color', $wallet['color'] ?: '#134686')); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Icon Bootstrap</label>
                            <input type="text" name="icon" class="form-control"
                                value="<?= esc(old('icon', $wallet['icon'] ?: 'bi-wallet2')); ?>"
                                placeholder="Contoh: bi-wallet2, bi-bank, bi-cash-stack">
                            <div class="form-text">
                                Gunakan class icon dari Bootstrap Icons, misalnya <code>bi-bank</code>.
                            </div>
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
                    <p class="text-muted mb-1">Saldo Saat Ini</p>
                    <h3 class="fw-bold">
                        Rp
                        <?= number_format((float) $wallet['current_balance'], 0, ',', '.'); ?>
                    </h3>

                    <hr>

                    <p class="text-muted mb-1">Saldo Awal</p>
                    <h5 class="fw-semibold">
                        Rp
                        <?= number_format((float) $wallet['initial_balance'], 0, ',', '.'); ?>
                    </h5>

                    <div class="alert alert-info mt-3 mb-0">
                        Saldo tidak diedit langsung dari halaman ini agar riwayat transaksi tetap akurat.
                        Untuk mengubah saldo, tambahkan transaksi penyesuaian.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>