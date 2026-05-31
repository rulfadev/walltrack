<?= $this->extend('/layout'); ?>
<?= $this->section('content'); ?>

<?php
$typeLabels = [
    'cash' => [
        'label' => 'Cash',
        'icon' => 'bi-cash-stack',
    ],
    'bank' => [
        'label' => 'Bank',
        'icon' => 'bi-bank',
    ],
    'ewallet' => [
        'label' => 'E-Wallet',
        'icon' => 'bi-phone',
    ],
    'saving' => [
        'label' => 'Tabungan',
        'icon' => 'bi-piggy-bank',
    ],
];
?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Wallet</h2>
            <p class="text-muted mb-0">
                Kelola dompet, rekening bank, e-wallet, dan tabungan pribadi.
            </p>
        </div>

        <a href="<?= base_url('/wallets/create'); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Wallet
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

    <div class="row g-3">
        <?php if (!empty($wallets)): ?>
            <?php foreach ($wallets as $wallet): ?>
                <?php
                $type = $typeLabels[$wallet['type']] ?? [
                    'label' => ucfirst((string) $wallet['type']),
                    'icon' => 'bi-wallet2',
                ];
                ?>

                <div class="col-md-4" id="wallet-card-<?= esc($wallet['id']); ?>">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        <?= esc($wallet['name']); ?>
                                    </h5>

                                    <span class="badge bg-light text-dark">
                                        <i class="bi <?= esc($type['icon']); ?> me-1"></i>
                                        <?= esc($type['label']); ?>
                                    </span>

                                    <?php if ((int) $wallet['is_default'] === 1): ?>
                                        <span class="badge bg-primary">
                                            Utama
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <i class="bi <?= esc($wallet['icon'] ?: $type['icon']); ?> fs-3 text-primary"></i>
                            </div>

                            <p class="text-muted mb-1">Saldo Saat Ini</p>
                            <h4 class="fw-bold mb-0">
                                Rp<?= number_format((float) $wallet['current_balance'], 0, ',', '.'); ?>
                            </h4>

                            <div class="d-flex gap-2 mt-4">
                                <?php if ((int) $wallet['is_default'] !== 1): ?>
                                    <form action="<?= base_url('/wallets/set-default/' . $wallet['id']); ?>" method="post">
                                        <?= csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            Jadikan Utama
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <a href="<?= base_url('/wallets/edit/' . $wallet['id']); ?>"
                                    class="btn btn-sm btn-outline-secondary ms-auto">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="deleteWallet(<?= esc($wallet['id']); ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-wallet2 fs-1 text-muted d-block mb-3"></i>
                        <h5 class="fw-bold">Belum ada wallet</h5>
                        <p class="text-muted">
                            Tambahkan wallet pertama untuk mulai mencatat transaksi.
                        </p>
                        <a href="<?= base_url('/wallets/create'); ?>" class="btn btn-primary">
                            Tambah Wallet
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

    function deleteWallet(id) {
        if (!confirm('Yakin ingin menghapus wallet ini? Wallet yang sudah punya transaksi tidak bisa dihapus.')) {
            return;
        }

        fetch('<?= base_url('/wallets/delete'); ?>', {
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
                        message: 'Gagal menghapus wallet.'
                    };
                }

                return data;
            })
            .then(data => {
                updateCsrfToken(data.csrfHash);

                if (data.success) {
                    const card = document.getElementById('wallet-card-' + id);

                    if (card) {
                        card.remove();
                    } else {
                        location.reload();
                    }

                    return;
                }

                alert(data.message || 'Wallet gagal dihapus.');
            })
            .catch(error => {
                alert(error.message || 'Terjadi kesalahan saat menghapus wallet.');
            });
    }
</script>

<?= $this->endSection(); ?>