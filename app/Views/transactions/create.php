<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>
<style>
    form {
        justify-content: center;
    }

    form .form-control {
        background: #eff1f4;
        border: none;
        border-radius: 3px;
        box-shadow: none;
        outline: none;
        color: inherit;
        text-indent: 9px;
        height: 45px;
        margin-bottom: 10px;
        min-width: fit-content;
        width: 100%;
    }

    @media screen and (min-width: 768px) {
        form .form-control {
            min-width: 400px;
            margin-right: 10px;
            margin-bottom: 0;
        }
    }

    form .form-group {
        margin-bottom: 15px;
    }

    form .btn {
        padding: 16px 20px;
        border: none;
        box-shadow: none;
        text-shadow: none;
        opacity: 0.9;
        text-transform: uppercase;
        font-weight: bold;
        font-size: 13px;
        letter-spacing: 0.4px;
        line-height: 1;
        margin: 0 5px;
    }
</style>
<div class="container my-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Add Transaction</h2>
            <p class="text-muted mb-0">Add your transaction details below.</p>
        </div>
    </div>

    <!-- Message Alert -->
    <?= view('layouts\elements\_message_block') ?>

    <!-- Card with Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <form action="/transactions/store" method="post">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="type">Transaction Type</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="debit">Debt (Income)</option>
                        <option value="kredit">Credit (Expense)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="transaction_date">Date</label>
                    <input type="date" class="form-control" id="transaction_date" name="transaction_date" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
                <a href="/transactions" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>