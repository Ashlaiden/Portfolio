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
                        <a class="btn btn-sm btn-info" href="<?= base_url('project/' . esc($project['id'])) ?>">View
                            on Site</a>
                        <a class="btn btn-sm btn-success"
                            href="<?= admin_url('project/edit-project/' . esc($project['id'])) ?>">Edit</a>
                        <form method="post" action="<?= admin_url('project/delete-project') ?>"
                            onsubmit="return confirm('Are you sure?');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="project_id" value="<?= esc($project['id']) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>

                    <h6 class="text-start text-md-center mb-4">Project Detail</h6>
                    <dl class="row g-2">
                        <dt class="col-12 col-sm-4">ID</dt>
                        <dd class="col-12 col-sm-8"><?= esc($project['id']) ?></dd>

                        <dt class="col-12 col-sm-4">Title</dt>
                        <dd class="col-12 col-sm-8"><?= esc($project['title']) ?></dd>

                        <dt class="col-12 col-sm-4">Slug</dt>
                        <dd class="col-12 col-sm-8"><?= esc($project['slug']) ?></dd>

                        <dt class="col-12 col-sm-4">Programming Language</dt>
                        <dd class="col-12 col-sm-8"><?= esc($project['programming_language']) ?></dd>

                        <dt class="col-12 col-sm-4">Framework</dt>
                        <dd class="col-12 col-sm-8"><?= esc($project['framework']) ?></dd>

                        <dt class="col-12 col-sm-4">Created At</dt>
                        <dd class="col-12 col-sm-8"><?= date('F j, Y - g:i A', strtotime($project['created_at'])) ?>
                        </dd>

                        <dt class="col-12 col-sm-4">Updated At</dt>
                        <dd class="col-12 col-sm-8"><?= date('F j, Y - g:i A', strtotime($project['updated_at'])) ?>
                        </dd>
                    </dl>

                    <div class="border rounded p-3 mt-3">
                        <h6>Description</h6>
                        <p class="mb-0"><?= esc($project['description']) ?></p>
                    </div>

                    <div class="border rounded p-3 mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Cover Image</h6>
                            <a class="btn btn-sm btn-success"
                                href="<?= admin_url('project/detail/' . esc($project['id']) . '/edit-image') ?>">Change</a>
                        </div>
                        <img src="<?= base_url(esc($project['image'])) ?>" alt="Cover" class="img-fluid w-100"
                            style="max-height:20rem; object-fit:contain;">
                    </div>

                </div>

                <!-- Gallery Section -->
                <div class="bg-secondary rounded p-3 p-md-4 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Project Gallery</h6>
                        <a class="btn btn-sm btn-success"
                            href="<?= admin_url('project/detail/' . esc($project['id']) . '/new-gallery-image') ?>">+
                            Add Image</a>
                    </div>
                    <?php if (!empty($galleries)): ?>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                            <?php foreach (esc($galleries) as $gallery): ?>
                                <div class="col">
                                    <div class="card h-100 bg-secondary border border-2 border-dark">
                                        <img src="<?= base_url(esc($gallery['image_path'])) ?>" class="card-img-top img-fluid"
                                            style="max-height:15rem; object-fit:contain;" alt="<?= esc($gallery['title']) ?>">
                                        <div class="card-body p-2">
                                            <h6 class="card-title text-truncate mb-1"><?= esc($gallery['title']) ?></h6>
                                            <div class="d-flex justify-content-between">
                                                <form method="post"
                                                    action="<?= admin_url('project/detail/delete-gallery-image') ?>"
                                                    onsubmit="return confirm('Delete?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id" value="<?= esc($gallery['id']) ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Del</button>
                                                </form>
                                                <a class="btn btn-sm btn-success"
                                                    href="<?= admin_url('project/detail/' . esc($gallery['id']) . '/edit-gallery-image') ?>">Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-4">
                            <p class="mb-3">No images in gallery.</p>
                            <a class="btn btn-sm btn-success"
                                href="<?= admin_url('project/detail/' . esc($project['id']) . '/new-gallery-image') ?>">+
                                Add Image</a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>