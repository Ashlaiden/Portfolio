<?= $this->extend('base/base') ?>

<?= $this->section('meta_title') ?><?= esc($meta_title) ?><?= $this->endsection() ?>

<?php $this->section('content') ?>

<main class="container-fluid px-0 py-5">
    <!-- Project Details Full-Width -->
    <div class="row justify-content-center gx-0">
        <div class="col-12">
            <div class="border border-1 border-dark rounded p-2 p-md-4">
                <!-- Image full width -->
                <div class="mb-3 mb-md-4 text-center">
                    <img src="<?= base_url(esc($project['image'])) ?>" alt="<?= esc($project['title']) ?>"
                        class="img-fluid w-100" style="max-height:25rem; object-fit:contain;">
                </div>
                <!-- Title -->
                <h1 class="text-center h3 fs-1 mt-2 mt-md-3">
                    <code class="text-dark display-6"><?= esc($project['title']) ?></code>
                </h1>
                <hr class="border-1 border-dark opacity-100">
                <!-- Details list full width columns -->
                <dl class="row gx-0">
                    <dt class="col-12 col-md-3 text-truncate">Programming Language</dt>
                    <dd class="col-12 col-md-9"><?= esc($project['programming_language']) ?></dd>

                    <dt class="col-12 col-md-3 text-truncate">Framework</dt>
                    <dd class="col-12 col-md-9"><?= esc($project['framework']) ?></dd>

                    <dt class="col-12 col-md-3 text-truncate">Created At</dt>
                    <dd class="col-12 col-md-9"><?= date('F j, Y - g:i A', strtotime($project['created_at'])) ?></dd>
                </dl>
                <!-- Description full width -->
                <div class="border rounded p-2 p-md-4 mt-3 mt-md-4">
                    <figure>
                        <blockquote class="blockquote">
                            <p>Description</p>
                        </blockquote>
                        <figcaption class="blockquote-footer text-wrap">
                            <?= esc($project['description']) ?>
                        </figcaption>
                    </figure>
                </div>
            </div>
            <!-- Gallery full width -->
            <div class="border border-1 border-dark rounded p-2 p-md-4 mt-3 mt-md-4">
                <h5 class="text-center mb-3 mb-md-4">Project Gallery</h5>
                <hr class="border-1 border-dark opacity-100">
                <?php if (isset($galleries) && count($galleries) > 0): ?>
                    <div class="row row-cols-1 row-cols-md-3 gx-2 gx-md-4 gy-2 gy-md-4">
                        <?php foreach (esc($galleries) as $gallery): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <img src="<?= base_URL(esc($gallery['image_path'])) ?>" class="card-img-top img-fluid w-100"
                                        style="max-height:20rem; object-fit:contain;" alt="<?= esc($gallery['title']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title text-center"><?= esc($gallery['title']) ?></h5>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column align-items-center justify-content-center p-3 p-md-5">
                        <span class="text-center pb-2 pb-md-3">There is No Image in Gallery for This Project!</span>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</main>

<?php $this->endSection() ?>