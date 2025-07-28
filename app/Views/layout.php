<!doctype html>
<html lang="en">

<head>
    <?= $this->include('layouts/_header'); ?>
</head>

<body>
    <?= $this->include('layouts/_navbar'); ?>
    <main role="main" style="min-height: 80vh;">
        <?= $this->renderSection('content'); ?>
    </main>
    <?= $this->include('layouts/_footer'); ?>
</body>

</html>