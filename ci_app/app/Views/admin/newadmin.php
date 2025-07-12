<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= esc($meta_title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">
    <?= view('admin/base/navbar') ?>

    <!-- New Admin Form -->
    <div class="container-fluid px-2 px-md-4 pt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="bg-secondary rounded p-3 p-md-4">
                    <h6 class="text-center mb-3 mb-md-4">Add New Admin</h6>
                    <form method="post" action="<?= admin_url('setting/new-admin') ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <?php if (session()->has('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="row g-3">
                            <div class="col-12 col-sm-4 d-flex align-items-center">
                                <label for="username" class="form-label mb-0">UserName</label>
                            </div>
                            <div class="col-12 col-sm-8">
                                <input type="text" name="username" class="form-control" id="username"
                                    value="<?= old('username') ?>">
                                <div class="form-text">Just for display in dashboard.</div>
                            </div>

                            <div class="col-12 col-sm-4 d-flex align-items-center">
                                <label for="email" class="form-label mb-0">Email</label>
                            </div>
                            <div class="col-12 col-sm-8">
                                <input type="email" name="email" class="form-control" id="email"
                                    value="<?= old('email') ?>">
                                <div class="form-text">Enter a valid accessible email.</div>
                            </div>

                            <div class="col-12 col-sm-4 d-flex align-items-center">
                                <label for="password" class="form-label mb-0">Password</label>
                            </div>
                            <div class="col-12 col-sm-8">
                                <input type="password" name="password" class="form-control" id="password"
                                    value="<?= old('password') ?>">
                                <div class="form-text">Must be at least 6 characters.</div>
                            </div>

                            <div class="col-12 col-sm-4 d-flex align-items-center">
                                <label class="form-label mb-0">Admin Role</label>
                            </div>
                            <div class="col-12 col-sm-8">
                                <div class="d-flex flex-column flex-sm-row gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="adminrole" id="roleSuper"
                                            value="superadmin" <?= esc($adminrole !== 'superadmin' ? 'disabled' : '') ?>>
                                        <label class="form-check-label" for="roleSuper">Super Admin</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="adminrole" id="roleAdmin"
                                            value="admin" checked>
                                        <label class="form-check-label" for="roleAdmin">Admin</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="adminrole" id="roleEditor"
                                            value="editor">
                                        <label class="form-check-label" for="roleEditor">Editor</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4 d-flex align-items-center">
                                <label class="form-label mb-0">Activation Status</label>
                            </div>
                            <div class="col-12 col-sm-8">
                                <div class="form-check form-switch">
                                    <input name="Active" class="form-check-input" type="checkbox" role="switch"
                                        id="statusSwitch" checked>
                                    <label class="form-check-label" for="statusSwitch">Active</label>
                                </div>
                                <div class="form-text">Check to activate account (default = active).</div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-success w-100">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>