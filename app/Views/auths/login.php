<?= $this->extend('/layout'); ?>
<?= $this->section('content') ?>
<div class="section light-background">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-12 col-md-6 col-xl-7">
                <div class="d-flex justify-content-center">
                    <div class="col-12 col-xl-9">
                        <a href="<?= base_url(); ?>" class="logo d-flex align-items-center">
                            <h1 class="sitename"><span style="color: var(--accent-color);">Wall</span>Track</span></h1>
                        </a>
                        <hr class="border-primary-subtle mb-4">
                        <h2 class="h1 mb-4">Take Control of Your Finances.</h2>
                        <p class="lead mb-5">Walltrack is your personal finance tracker — designed to help you record
                            income, monitor expenses,
                            and
                            manage budgets in one intuitive platform.</p>
                        <div class="text-endx">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                                <path
                                    d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-5">
                <div class="card border-0 rounded-4">
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <h3>Sign in</h3>
                                    <p>Don't have an account? <a href="<?= url_to('signup') ?>">Sign up</a></p>
                                    <?= view('layouts\elements\_message_block') ?>
                                </div>
                            </div>
                        </div>
                        <form action="<?= url_to('login') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row gy-3 overflow-hidden">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email"
                                            class="form-control <?php if (session('errors.email')): ?>is-invalid<?php endif ?>"
                                            name="email" id="email" placeholder="name@example.com"
                                            value="<?= old('email') ?>">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="invalid-feedback">
                                            <?= ucfirst(session('errors.email')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password"
                                            class="form-control <?php if (session('errors.password')): ?>is-invalid<?php endif ?>"
                                            name="password" id="password" value="" placeholder="Password">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="invalid-feedback">
                                            <?= ucfirst(session('errors.password')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn btn-login btn-lg" type="submit">Log in</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-1 flex-column flex-md-row justify-content-md-end mt-4">
                                    Forgot Password? <a href="#!">Forgot</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>