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

    <!-- Projects Items Start -->
    <div class="container-fluid px-4 pt-4">

        <div class="d-flex justify-content-center align-items-center">
            <h5 class="mb-1 text-center">Trash</h5>
        </div>
        <hr class="border-light">

        <?php if (!empty($trashes) && count($trashes) > 0): ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach (esc($trashes) as $gallery): ?>
                    <div class="col">
                        <div class="card bg-secondary h-100">
                            <img src="<?= base_URL(esc($gallery['image_path'])) ?>" class="card-img-top img-fluid"
                                style="height: 15rem; object-fit:contain;" alt="<?= esc($gallery['title']) ?>">
                            <div class="card-body">
                                <h6 class="card-title text-truncate"><?= esc($gallery['title']) ?></h6>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <small class="text-muted">DA: <?= date('M d, Y', strtotime($gallery['deleted_at'])) ?></small>
                            </div>
                            <div class="px-4 pb-2 d-flex justify-content-between align-items-center">
                                <form method="post" action="<?= admin_url('gallery/trash/survive') ?>">

                                    <?= csrf_field() ?>

                                    <input type="hidden" name="gallery_id" value="<?= esc($gallery['id']) ?>">
                                    <button type="submit" class="btn btn-sm w-45"
                                        style="background-color: green; color: white;">survive</button>
                                </form>
                                <form method="post" action="<?= admin_url('gallery/trash/delete') ?>"
                                    onsubmit="return confirm('Are you sure you want to delete this Image?');">

                                    <?= csrf_field() ?>

                                    <input type="hidden" name="gallery_id" value="<?= esc($gallery['id']) ?>">
                                    <button type="submit" class="btn btn-sm w-45"
                                        style="background-color: red; color: white;">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column align-items-center justify-content-center p-5">
                <span class="text-center">There is No Project in Trash!</span>
            </div>
        <?php endif ?>

    </div>
    <!-- Projects Items End -->

</div>

<?= $this->endSection() ?>