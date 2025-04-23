<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">
    <?= view('admin/base/navbar') ?>

    <div class="container-fluid px-4 pt-4">
        <div class="col-12">
            <div class="bg-secondary rounded h-100 p-4">
                <h5 class="mb-4 text-center">Settings</h5>
                <nav>
                    <div class="nav nav-tabs flex-nowrap overflow-auto" id="nav-tab" role="tablist">
                        <?php if (session()->get('admin_role') == 'owner' || session()->get('admin_role') == 'superadmin'): ?>
                            <button class="nav-link me-2 active" id="nav-basic-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-basic" type="button" role="tab" aria-controls="nav-basic"
                                aria-selected="true">Basic</button>
                            <button class="nav-link me-2" id="nav-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Profile</button>
                            <button class="nav-link me-2" id="nav-admin-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-admin" type="button" role="tab" aria-controls="nav-admin"
                                aria-selected="false">Admin</button>
                        <?php elseif (session()->get('admin_role') == 'admin'): ?>
                            <button class="nav-link me-2 active" id="nav-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="true">Profile</button>
                            <button class="nav-link me-2" id="nav-admin-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-admin" type="button" role="tab" aria-controls="nav-admin"
                                aria-selected="false">Admin</button>
                        <?php else: ?>
                            <button class="nav-link me-2 active" id="nav-basic-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-basic" type="button" role="tab" aria-controls="nav-basic"
                                aria-selected="true">Basic</button>
                            <button class="nav-link me-2" id="nav-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Profile</button>
                        <?php endif ?>
                    </div>
                </nav>

                <?php if (session()->has('errors')): ?>
                    <div class="text-danger my-3">
                        <ul>
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="tab-content pt-3" id="nav-tabContent">

                    <?php if (session()->get('admin_role') == 'owner' || session()->get('admin_role') == 'superadmin'): ?>
                        <!-- Basic Tab -->
                        <div class="tab-pane fade show active" id="nav-basic" role="tabpanel"
                            aria-labelledby="nav-basic-tab">
                            <form method="post" action="<?= admin_url('/setting/update') ?>" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <dl class="row mb-0">
                                    <?php foreach ($settings as $key => $value): ?>
                                        <dt class="col-12 col-md-4 d-flex align-items-center">
                                            <label for="<?= esc($key) ?>" class="form-label"><?= $key ?>:</label>
                                        </dt>
                                        <dd class="col-12 col-md-8 mb-2">
                                            <input type="text" name="<?= esc($key) ?>" class="form-control"
                                                id="<?= esc($key) ?>" value="<?= old(esc($key), esc($value)) ?>">
                                        </dd>
                                        <div id="titleHelp" class="form-text mb-3 col-12 col-md-8 offset-md-4">
                                            <?php
                                            switch ($key) {
                                                case 'admin_prefix':
                                                    echo "<p>This is The Web Path Use in Url of AdminPanel, Don't Write '/' in Start and end of Path, you can use in middle of path.</p>";
                                                    break;
                                                default:
                                                    echo '<p>🚫 Role not recognized.</p>';
                                                    break;
                                            }
                                            ?>
                                        </div>
                                        <hr class="border-1 border-dark opacity-100 mb-3 col-12">
                                    <?php endforeach ?>
                                </dl>
                                <button type="submit" class="btn btn-primary w-100 m-2">Save</button>
                            </form>
                        </div>

                        <!-- Profile Tab -->
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <form method="post" action="<?= admin_url('/setting/profile/update') ?>"
                                enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= esc(session()->get('admin_id')) ?>">
                                <dl class="row mb-0">
                                    <!-- Username -->
                                    <dt class="col-12 col-md-4 d-flex align-items-center">
                                        <label for="username" class="form-label">UserName:</label>
                                    </dt>
                                    <dd class="col-12 col-md-8 mb-2">
                                        <input type="text" name="username" class="form-control" id="username"
                                            value="<?= old('username', esc($logedinadmin['username'])) ?>">
                                    </dd>
                                    <hr class="border-1 border-dark opacity-100 mb-3 col-12">

                                    <!-- Email -->
                                    <dt class="col-12 col-md-4 d-flex align-items-center">
                                        <label for="email" class="form-label">Email:</label>
                                    </dt>
                                    <dd class="col-12 col-md-8 mb-2">
                                        <input type="text" name="email" class="form-control" id="email"
                                            value="<?= old('email', esc($logedinadmin['email'])) ?>">
                                    </dd>
                                    <hr class="border-1 border-dark opacity-100 mb-3 col-12">

                                    <!-- Current Password -->
                                    <dt class="col-12 col-md-4 d-flex align-items-center">
                                        <label for="currentpassword" class="form-label">Current Password:</label>
                                    </dt>
                                    <dd class="col-12 col-md-8 mb-2">
                                        <input type="password" name="currentpassword" class="form-control"
                                            id="currentpassword">
                                    </dd>
                                    <hr class="border-1 border-dark opacity-100 mb-3 col-12">

                                    <!-- New Password -->
                                    <dt class="col-12 col-md-4 d-flex align-items-center">
                                        <label for="password" class="form-label">New Password:</label>
                                    </dt>
                                    <dd class="col-12 col-md-8 mb-2">
                                        <input type="password" name="password" class="form-control" id="password">
                                    </dd>
                                    <hr class="border-1 border-dark opacity-100 mb-3 col-12">
                                </dl>
                                <button type="submit" class="btn btn-primary w-100 m-2"
                                    style="background-color: green; color: white;">Update</button>
                            </form>
                        </div>

                        <!-- Admin Tab -->
                        <div class="tab-pane fade" id="nav-admin" role="tabpanel" aria-labelledby="nav-admin-tab">
                            <div class="container-fluid">
                                <div class="bg-secondary text-center rounded p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <h6 class="mb-0">All Admins</h6>
                                        <a class="btn btn-primary" href="<?= admin_url('setting/new-admin') ?>"
                                            style="background-color: green; color: white;">+ Add Admin</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                                            <thead>
                                                <tr class="table-dark text-white">
                                                    <th scope="col">UserName</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Role</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Last Login</th>
                                                    <th scope="col">Edit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach (esc($admins) as $admin): ?>
                                                    <tr>
                                                        <td><?= esc($admin['username']) ?></td>
                                                        <td><?= esc($admin['email']) ?></td>
                                                        <td><?= esc($admin['status']) ?></td>
                                                        <td><?= esc($admin['role']) ?></td>
                                                        <td><?= date('F j, Y - g:i A', strtotime($admin['updated_at'])) ?></td>
                                                        <td>
                                                            <?php if (session()->get('admin_id') !== $admin['id']): ?>
                                                                <a class="btn btn-sm"
                                                                    href="<?= admin_url('setting/edit-admin/' . esc($admin['id'])) ?>"
                                                                    style="background-color: green; color: white;">Edit</a>
                                                            <?php endif ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php elseif (session()->get('admin_role') == 'admin'): ?>
                        <!-- Profile Tab -->
                        <div class="tab-pane fade show active" id="nav-profile" role="tabpanel"
                            aria-labelledby="nav-profile-tab">
                            <!-- same profile form as above with $logedinadmin id -->
                        </div>

                        <!-- Admin Tab -->
                        <div class="tab-pane fade" id="nav-admin" role="tabpanel" aria-labelledby="nav-admin-tab">
                            <!-- same admin table but no add button -->
                        </div>

                    <?php else: ?>
                        <!-- Basic Tab -->
                        <div class="tab-pane fade show active" id="nav-basic" role="tabpanel"
                            aria-labelledby="nav-basic-tab">
                            <!-- same basic form -->
                        </div>

                        <!-- Profile Tab -->
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <!-- same profile form -->
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>