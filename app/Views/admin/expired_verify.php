<?= $this->extend('base/main') ?>

<?= $this->section('meta_title') ?>
<?= esc($meta_title) ?>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?= $this->endSection() ?>

<?= $this->section('style') ?>
.gradient-custom {
/* fallback for old browsers */
background: #4a4a4a;
/* Chrome 10-25, Safari 5.1-6 */
background: -webkit-linear-gradient(to right, #4a4a4a, #1e1e1e);
/* Modern browsers */
background: linear-gradient(to right, #4a4a4a, #1e1e1e);
}
<?= $this->endSection() ?>

<?= $this->section('container_class') ?>
col-12
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="min-vh-100 gradient-custom d-flex align-items-center justify-content-center">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <h2 class="fw-bold mb-4 text-danger text-uppercase">Expired!</h2>
                        <p class="text-white-50 mb-5">Verification time has expired.<br>Please sign in again.</p>
                        <a href="<?= admin_url('login') ?>" class="btn btn-outline-light btn-lg px-5 w-100">Back to
                            Sign-in</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="d-block w-100 bg-dark text-white text-center py-3">
    <?= view_cell('\App\Libraries\Footer::load') ?>
</footer>

<?= $this->endSection() ?>