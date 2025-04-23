<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= esc($meta_title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">
    <?= view('admin/base/navbar') ?>

    <!-- Admin Projects List -->
    <div class="container-fluid px-2 px-md-4 pt-4">
        <div class="d-flex justify-content-center position-relative mb-3">
            <h5 class="mb-1">Projects</h5>
            <a class="btn btn-success btn-sm position-absolute top-0 end-0"
                href="<?= admin_url('project/new-project') ?>">+ Add Project</a>
        </div>
        <hr class="text-white">

        <?php if (!empty($projects)): ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach (esc($projects) as $project): ?>
                    <div class="col">
                        <div class="card bg-secondary h-100">
                            <img src="<?= base_URL(esc($project['image'])) ?>" class="card-img-top img-fluid"
                                style="height: 15rem; object-fit:contain;" alt="<?= esc($project['title']) ?>">
                            <div class="card-body">
                                <h6 class="card-title text-truncate"><?= esc($project['title']) ?></h6>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?= date('M d, Y', strtotime($project['updated_at'])) ?></small>
                                <a class="btn btn-sm btn-success"
                                    href="<?= admin_url('project/detail/' . esc($project['id'])) ?>">Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center p-4">
                <p class="mb-3">There are no projects in the database.</p>
                <a class="btn btn-success" href="<?= admin_url('project/new-project') ?>">+ Add Project</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>