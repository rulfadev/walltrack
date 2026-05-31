<?= $this->extend('/layout'); ?>
<?= $this->section('content'); ?>

<?php
$oldType = old('type', $transaction['type']);
?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit Transaksi</h2>
            <p class="text-muted mb-0">
                Perbarui transaksi. Saldo wallet akan otomatis disesuaikan.
            </p>
        </div>

        <a href="<?= base_url('/transactions'); ?>" class="btn btn-outline-secondary">
            Kembali
        </a>
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

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="<?= base_url('/transactions/update/' . $transaction['id']); ?>" method="post">
                <?= csrf_field(); ?>

                <div class="mb-3">
                    <label class="form-label">Jenis Transaksi</label>
                    <select name="type" id="transactionType" class="form-select" required>
                        <option value="expense" <?= $oldType === 'expense' ? 'selected' : ''; ?>>
                            Pengeluaran
                        </option>
                        <option value="income" <?= $oldType === 'income' ? 'selected' : ''; ?>>
                            Pemasukan
                        </option>
                        <option value="transfer" <?= $oldType === 'transfer' ? 'selected' : ''; ?>>
                            Transfer
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Wallet Asal</label>
                    <select name="wallet_id" id="walletId" class="form-select" required>
                        <option value="">Pilih Wallet</option>
                        <?php foreach ($wallets as $wallet) : ?>
                            <option
                                value="<?= esc($wallet['id']); ?>"
                                <?= old('wallet_id', $transaction['wallet_id']) == $wallet['id'] ? 'selected' : ''; ?>
                            >
                                <?= esc($wallet['name']); ?>
                                — Rp<?= number_format((float) $wallet['current_balance'], 0, ',', '.'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3" id="targetWalletGroup">
                    <label class="form-label">Wallet Tujuan</label>
                    <select name="transfer_to_wallet_id" id="targetWalletId" class="form-select">
                        <option value="">Pilih Wallet Tujuan</option>
                        <?php foreach ($wallets as $wallet) : ?>
                            <option
                                value="<?= esc($wallet['id']); ?>"
                                <?= old('transfer_to_wallet_id', $transaction['transfer_to_wallet_id']) == $wallet['id'] ? 'selected' : ''; ?>
                            >
                                <?= esc($wallet['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3" id="categoryGroup">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" id="categoryId" class="form-select">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($categories as $category) : ?>
                            <option
                                value="<?= esc($category['id']); ?>"
                                data-type="<?= esc($category['type']); ?>"
                                <?= old('category_id', $transaction['category_id']) == $category['id'] ? 'selected' : ''; ?>
                            >
                                <?= esc($category['name']); ?>
                                — <?= $category['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="form-text" id="categoryHelp">
                        Kategori akan otomatis menyesuaikan jenis transaksi.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nominal</label>
                    <input
                        type="number"
                        name="amount"
                        class="form-control"
                        min="1"
                        step="0.01"
                        value="<?= esc(old('amount', $transaction['amount'])); ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input
                        type="date"
                        name="transaction_date"
                        class="form-control"
                        value="<?= esc(old('transaction_date', $transaction['transaction_date'])); ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="description" class="form-control" rows="3"><?= esc(old('description', $transaction['description'])); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const typeSelect = document.getElementById('transactionType');
    const targetWalletGroup = document.getElementById('targetWalletGroup');
    const categoryGroup = document.getElementById('categoryGroup');
    const targetWalletId = document.getElementById('targetWalletId');
    const categoryId = document.getElementById('categoryId');
    const categoryHelp = document.getElementById('categoryHelp');

    function toggleTransactionFields() {
        const type = typeSelect.value;
        const isTransfer = type === 'transfer';

        targetWalletGroup.style.display = isTransfer ? 'block' : 'none';
        categoryGroup.style.display = isTransfer ? 'none' : 'block';

        targetWalletId.required = isTransfer;
        categoryId.required = !isTransfer;

        filterCategoryOptions(type);
    }

    function filterCategoryOptions(type) {
        let selectedIsValid = true;
        let visibleCount = 0;

        Array.from(categoryId.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const optionType = option.getAttribute('data-type');
            const shouldShow = optionType === type;

            option.hidden = !shouldShow;

            if (shouldShow) {
                visibleCount++;
            }

            if (option.selected && !shouldShow) {
                selectedIsValid = false;
            }
        });

        if (!selectedIsValid && categoryId.options.length > 0) {
            categoryId.value = '';
        }

        if (type === 'transfer') {
            categoryHelp.textContent = 'Transfer tidak membutuhkan kategori.';
            return;
        }

        if (visibleCount === 0) {
            categoryHelp.textContent = type === 'income'
                ? 'Belum ada kategori Pemasukan. Buat kategori Pemasukan terlebih dahulu.'
                : 'Belum ada kategori Pengeluaran. Buat kategori Pengeluaran terlebih dahulu.';
        } else {
            categoryHelp.textContent = 'Kategori sudah difilter sesuai jenis transaksi.';
        }
    }

    typeSelect.addEventListener('change', toggleTransactionFields);
    toggleTransactionFields();
</script>

<?= $this->endSection(); ?>