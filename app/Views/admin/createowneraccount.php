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
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-3 p-md-5 text-center">
                        <h2 class="fw-bold mb-4 text-uppercase">Create Owner Account</h2>
                        <p class="text-white-50 mb-5 text-start">
                            If you are on this page it means your site is just published and it’s your
                            first time accessing the Admin Panel, or all owner/admin accounts have been deleted.
                            Creating a new owner account will disable any other admin accounts until you enable
                            them again from the panel.<br><strong>Warning:</strong> Anyone who visits this page can
                            create an owner account.
                            Once the account is created, this page will never appear again!
                        </p>

                        <?php if (session()->has('errors')): ?>
                            <div class="text-danger my-3 text-start">
                                <ul>
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?= admin_url('create-owner-account') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-outline form-white mb-4">
                                <input name="username" type="text" class="form-control form-control-lg"
                                    value="<?= old('username') ?>" />
                                <label class="form-label">Username</label>
                            </div>

                            <div class="form-outline form-white mb-4">
                                <input name="email" type="email" class="form-control form-control-lg"
                                    value="<?= old('email') ?>" />
                                <label class="form-label">Email</label>
                            </div>

                            <div class="form-outline form-white mb-4">
                                <input name="password" type="password" class="form-control form-control-lg" />
                                <label class="form-label">Password</label>
                            </div>

                            <input type="hidden" name="role" value="owner">
                            <input type="hidden" name="status" value="1">

                            <button type="submit" class="btn btn-outline-light btn-lg w-100">Create Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>