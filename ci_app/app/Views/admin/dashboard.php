<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">
    <?= view('admin/base/navbar') ?>

    <!-- Stats Cards -->
    <div class="container-fluid px-2 px-md-4 pt-4">
        <div class="row g-2 g-md-4">
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-3 p-md-4">
                    <i class="fa fa-chart-line fa-2x fa-md-3x text-primary"></i>
                    <div class="ms-2 ms-md-3">
                        <p class="mb-1 mb-md-2 small">Today View</p>
                        <h6 class="mb-0 fs-5 fs-md-4"><?= esc($today_view) ?></h6>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-3 p-md-4">
                    <i class="fa fa-chart-bar fa-2x fa-md-3x text-primary"></i>
                    <div class="ms-2 ms-md-3">
                        <p class="mb-1 mb-md-2 small">Total View</p>
                        <h6 class="mb-0 fs-5 fs-md-4"><?= esc($total_view) ?></h6>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-3 p-md-4">
                    <i class="fa fa-chart-pie fa-2x fa-md-3x text-primary"></i>
                    <div class="ms-2 ms-md-3">
                        <p class="mb-1 mb-md-2 small">Total Projects</p>
                        <h6 class="mb-0 fs-5 fs-md-4"><?= esc($total_project) ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Projects Table -->
    <div class="container-fluid px-2 px-md-4 pt-4">
        <div class="bg-secondary text-center rounded p-3 p-md-4">
            <div class="d-flex flex-row align-items-start align-md-center justify-content-between mb-3">
                <h6 class="mb-2 mb-md-0">All Projects</h6>
                <a class="btn btn-success btn-sm" href="<?= admin_url('project/new-project') ?>">+ Add Project</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Created</th>
                            <th scope="col">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (esc($projects) as $project) : ?>
                            <tr>
                                <td><?= esc($project['id']) ?></td>
                                <td><?= esc($project['title']) ?></td>
                                <td><?= esc($project['slug']) ?></td>
                                <td><?= date('M d, Y', strtotime($project['updated_at'])) ?></td>
                                <td><a class="btn btn-success btn-sm" href="<?= admin_url('project/detail/' . esc($project['id'])) ?>">Detail</a></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>