<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('layouts/_header'); ?>

    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
        }

        .error-container {
            margin-top: 10vh;
        }

        .error-icon {
            font-size: 4rem;
            color: #dc3545;
        }

        .stack-trace {
            background-color: #fff;
            padding: 1rem;
            border-radius: 6px;
            margin-top: 1rem;
            font-family: monospace;
            font-size: 0.9rem;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container text-center error-container">
        <div class="error-icon mb-3">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <h1 class="display-4"><?= esc($code) ?> - <?= esc($title ?? 'An Error Occurred') ?></h1>
        <p class="lead"><?= esc($message ?? 'Sorry, something went wrong.') ?></p>

        <?php if (ENVIRONMENT !== 'production'): ?>
            <?php if (!empty($exception) && $exception instanceof Throwable): ?>
                <div class="text-start mt-5">
                    <h5>Exception Details</h5>
                    <div class="stack-trace">
                        <strong><?= esc($exception->getMessage()) ?></strong><br>
                        <small><?= esc($exception->getFile()) ?> (Line <?= esc($exception->getLine()) ?>)</small>
                        <pre><?= esc($exception->getTraceAsString()) ?></pre>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="mt-4">
            <a href="<?= base_url() ?>" class="btn btn-primary">
                <i class="bi bi-house-door-fill"></i> Back to Home
            </a>
        </div>
    </div>
</body>

</html>