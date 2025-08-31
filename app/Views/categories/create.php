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
    }

    @media screen and (min-width: 768px) {
        form .form-control {
            min-width: 400px;
            margin-right: 10px;
            margin-bottom: 0;
        }
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
            <h2 class="fw-bold mb-1">Add Category</h2>
            <p class="text-muted mb-0">Add your categories on your transactions.</p>
        </div>
    </div>

    <!-- Message Alert -->
    <?= view('layouts\elements\_message_block') ?>

    <!-- Card with Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4 mx-auto">
            <form action="/categories/store" method="post">
                <?= csrf_field() ?>
                <div class="d-md-inline-flex">
                    <input class="form-control" type="text" name="name" id="name" placeholder="Your Category Name">
                    <button class="btn btn-primary" type="submit">Save</button>
                    <a href="/categories" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>