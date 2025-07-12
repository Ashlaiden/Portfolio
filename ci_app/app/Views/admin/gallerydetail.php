<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= esc($meta_title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">
    <?= view('admin/base/navbar') ?>

    <!-- Admin Project Detail -->
    <div class="container-fluid px-2 px-md-4 pt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="bg-secondary rounded p-3 p-md-4 position-relative">

                    <!-- Action Buttons -->
                    <div class="d-flex flex-row position-absolute top-0 end-0 mt-3 me-3 gap-2">
                        <a class="btn btn-sm btn-info" href="<?= base_url('gallery') . '#' . strtolower(preg_replace('/[^a-z0-9]+/', '-', esc($gallery['title'] . '-d' . $gallery['id']))) ?>">View
                            on Site</a>
                        <a class="btn btn-sm btn-success"
                            href="<?= admin_url('gallery/detail/' . esc($gallery['id'])) . '/edit' ?>">Edit</a>
                        <form method="post" action="<?= admin_url('gallery/delete') ?>"
                            onsubmit="return confirm('Are you sure?');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="gallery_id" value="<?= esc($gallery['id']) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>

                    <h6 class="text-start text-md-center mb-4">Project Detail</h6>
                    <dl class="row g-2">
                        <dt class="col-12 col-sm-4">ID</dt>
                        <dd class="col-12 col-sm-8"><?= esc($gallery['id']) ?></dd>

                        <dt class="col-12 col-sm-4">Title</dt>
                        <dd class="col-12 col-sm-8"><?= esc($gallery['title']) ?></dd>

                        <dt class="col-12 col-sm-4">Slug</dt>
                        <dd class="col-12 col-sm-8">#<?= strtolower(preg_replace('/[^a-z0-9]+/', '-', esc($gallery['title'] . '-d' . $gallery['id']))) ?></dd>

                        <dt class="col-12 col-sm-4">Created At</dt>
                        <dd class="col-12 col-sm-8"><?= date('F j, Y - g:i A', strtotime($gallery['created_at'])) ?>
                        </dd>

                        <dt class="col-12 col-sm-4">Updated At</dt>
                        <dd class="col-12 col-sm-8"><?= date('F j, Y - g:i A', strtotime($gallery['updated_at'])) ?>
                        </dd>
                    </dl>

                    <div class="border rounded p-3 mt-3">

                        <img src="<?= base_url(esc($gallery['image_path'])) ?>" alt="Image#<?= $gallery['title'] ?>" class="img-fluid w-100"
                            style="max-height:20rem; object-fit:contain;">
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>