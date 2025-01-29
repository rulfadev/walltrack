<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Tambah Transaksi</h2>

    <form action="/transactions/store" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="category_id">Kategori</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="type">Tipe Transaksi</label>
            <select class="form-control" id="type" name="type" required>
                <option value="debit">Debit (Pemasukan)</option>
                <option value="kredit">Kredit (Pengeluaran)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Jumlah</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="form-group">
            <label for="transaction_date">Tanggal</label>
            <input type="date" class="form-control" id="transaction_date" name="transaction_date" required>
        </div>
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="/transactions" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>