<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= esc($meta_title) ?>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">

    <?= view('admin/base/navbar') ?>

    <!-- Access Denied Start -->
    <div class="container-fluid px-4 py-5 d-flex align-items-center justify-content-center" style="min-height: 70vh;">
        <div class="bg-secondary border border-3 border-danger rounded p-4 text-center w-100" style="max-width: 400px;">
            <h1 class="display-4 text-danger mb-3">Access Denied</h1>
            <p class="mb-0">You do not have permission to view this page.</p>
        </div>
    </div>
    <!-- Access Denied End -->

</div>

<?= $this->endSection() ?>