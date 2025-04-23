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
            <div class="row g-3">
                <?php foreach (esc($trashes) as $project): ?>
                    <div class="col-12 col-sm-4">
                        <div class="card bg-secondary h-100">
                            <img src="<?= base_URL(esc($project['image'])) ?>" class="card-img-top img-fluid"
                                alt="<?= esc($project['title']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= esc($project['title']) ?></h5>
                                <p class="card-text mb-4">Deleted At: <?= date('M d, Y', strtotime($project['deleted_at'])) ?>
                                </p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between">
                                        <a href="<?= admin_url('project/trash/detail/' . esc($project['id'])) ?>"
                                            class="btn btn-sm w-45" style="background-color: green; color: white;">Detail</a>
                                        <form method="post" action="<?= admin_url('project/trash/delete') ?>"
                                            onsubmit="return confirm('Are you sure you want to delete this project?');">

                                            <?= csrf_field() ?>

                                            <input type="hidden" name="project_id" value="<?= esc($project['id']) ?>">
                                            <button type="submit" class="btn btn-sm w-45"
                                                style="background-color: red; color: white;">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
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