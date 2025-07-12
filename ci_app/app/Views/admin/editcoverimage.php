<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">
    <?= view('admin/base/navbar') ?>

    <!-- Change Project Image Form -->
    <div class="container-fluid px-2 px-md-4 pt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="bg-secondary rounded p-3 p-md-4">
                    <h6 class="text-center mb-3 mb-md-4">Change Project Image</h6>
                    <form method="post" action="<?= admin_url('/project/detail/edit-image') ?>"
                        enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="project_id" value="<?= esc($project_id) ?>">

                        <?php if (session()->has('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="row g-3 align-items-center">
                            <label for="cover_image" class="col-12 col-sm-4 col-form-label mb-0">New Cover Image</label>
                            <div class="col-12 col-sm-8">
                                <input name="cover_image" class="form-control form-control-lg bg-dark" id="cover_image"
                                    type="file" accept="image/*">
                                <div id="coverimagehelp" class="form-text">Choose cover image; it will display as the
                                    main project image.</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success w-100">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>