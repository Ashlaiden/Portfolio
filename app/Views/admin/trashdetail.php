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

    <!-- Project Trash Detail Start -->
    <div class="container-fluid px-4 pt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <?php if (session()->has('errors')): ?>
                    <div class="text-danger mb-3">
                        <ul>
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="bg-secondary rounded p-4">
                    <h6 class="text-center mb-4">Project Trash Detail</h6>
                    <dl class="row">
                        <?php $fields = [
                            'ID' => $project['id'],
                            'Title' => $project['title'],
                            'Slug' => $project['slug'],
                            'Programming Language' => $project['programming_language'],
                            'FrameWork' => $project['framework'],
                            'Created At' => date('F j, Y - g:i A', strtotime($project['created_at'])),
                            'Updated At' => date('F j, Y - g:i A', strtotime($project['updated_at'])),
                            '<span style="color: yellow;">Deleted At</span>' => date('F j, Y - g:i A', strtotime($project['deleted_at'])),
                        ]; ?>
                        <?php foreach ($fields as $label => $value): ?>
                            <dt class="col-12 col-md-4"><strong><?= $label ?></strong></dt>
                            <dd class="col-12 col-md-8 mb-3"><?= esc($value) ?></dd>
                        <?php endforeach; ?>
                    </dl>

                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <form method="post" action="<?= admin_url('project/trash/survive') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="project_id" value="<?= esc($project['id']) ?>">
                                <button type="submit" class="btn btn-success w-100">Survive</button>
                            </form>
                        </div>
                        <div class="col-6 col-md-3">
                            <form method="post" action="<?= admin_url('project/trash/delete') ?>"
                                onsubmit="return confirm('Are you sure you want to delete this project?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="project_id" value="<?= esc($project['id']) ?>">
                                <button type="submit" class="btn btn-danger w-100">Remove</button>
                            </form>
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-4">
                        <h6>Description</h6>
                        <p><?= esc($project['description']) ?></p>
                    </div>

                    <div class="border rounded p-3 mb-4 row">
                        <div class="col-12 col-md-4 mb-3 mb-md-0 d-flex align-items-center">
                            <strong>Cover Image</strong>
                        </div>
                        <div class="col-12 col-md-8">
                            <img src="<?= base_url(esc($project['image'])) ?>" class="img-fluid rounded"
                                alt="<?= esc($project['title']) ?>">
                        </div>
                    </div>
                </div>

                <div class="bg-secondary rounded p-4 mt-4">
                    <h6 class="text-center mb-4">Project Gallery</h6>
                    <?php if (!empty($galleries) && count($galleries) > 0): ?>
                        <div class="row g-3">
                            <?php foreach (esc($galleries) as $gallery): ?>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="card bg-secondary h-100">
                                        <img src="<?= base_URL(esc($gallery['image_path'])) ?>" class="card-img-top img-fluid"
                                            alt="<?= esc($gallery['title']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= esc($gallery['title']) ?></h5>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column align-items-center justify-content-center p-5">
                            <span>There is No Image in Gallery for This Project!</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Project Trash Detail End -->

</div>

<?= $this->endSection() ?>