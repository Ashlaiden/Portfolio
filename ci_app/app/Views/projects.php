<?= $this->extend('base/base') ?>

<?= $this->section('meta_title') ?><?= esc($meta_title) ?><?= $this->endsection() ?>

<?php $this->section('content') ?>

<main class="container mb-5 py-0 py-md-5">
    <h1 class="text-center h3 fs-1 mb-4">
        <code class="text-dark display-6">Projects</code>
    </h1>
    <hr class="border-1 border-dark opacity-100 mb-0 mb-md-5">

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        <?php foreach (esc($projects) as $project): ?>
            <div class="col">
                <div class="card bg-light border-3 border-dark h-100"
                    style="cursor: default; transition: transform 0.2s ease-in"
                    onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="<?= base_URL(esc($project['image'])) ?>" class="card-img-top img-fluid object-fit-contain"
                        style="height: 20rem; object-fit: contain;" alt="<?= esc($project['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($project['title']) ?></h5>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between mb-2">
                        <p class="mb-0 card-text text-secondary"><?= date('M d, Y', strtotime($project['updated_at'])) ?>
                        </p>
                        <a class="btn btn-sm px-4" style="background-color: black; color: white;"
                            href="<?= base_url('project/' . esc($project['id'])) ?>">Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</main>

<?php $this->endSection() ?>