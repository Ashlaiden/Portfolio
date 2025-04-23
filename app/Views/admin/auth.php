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

<section class="min-vh-100 gradient-custom d-flex align-items-center">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <form action="<?= admin_url('login') ?>" method="post" class="mb-md-5 mt-md-4 pb-5">

                            <?= csrf_field() ?>

                            <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                            <p class="text-white-50 mb-5">Please enter your login and password!</p>

                            <?php if (session()->has('errors')): ?>
                                <div class="text-danger my-3 text-start">
                                    <ul>
                                        <?php foreach (session('errors') as $error): ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="form-outline form-white mb-4">
                                <input name="email" type="email" id="typeEmailX" class="form-control form-control-lg"
                                    value="<?= old('email') ?>" />
                                <label class="form-label" for="typeEmailX">Email</label>
                            </div>

                            <div class="form-outline form-white mb-4">
                                <input name="password" type="password" id="typePasswordX"
                                    class="form-control form-control-lg" />
                                <label class="form-label" for="typePasswordX">Password</label>
                            </div>

                            <p class="small mb-5 pb-lg-2"><a class="text-white-50"
                                    href="<?= admin_url('login/forgot-password') ?>">Forgot password?</a></p>

                            <button class="btn btn-outline-light btn-lg px-5 w-100" type="submit">Login</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-dark text-white text-center py-3">
    <?= view_cell('\App\Libraries\Footer::load') ?>
</footer>

<?= $this->endSection() ?>