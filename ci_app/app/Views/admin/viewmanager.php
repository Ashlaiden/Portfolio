<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= esc($meta_title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">
    <?= view('admin/base/navbar') ?>

    <!-- Admin Projects List -->
    <div id="#accordionBackups" class="container-fluid px-2 px-md-4 pt-4">
        <?php foreach (esc($pages) as $page): ?>
            <div class="col-sm-12 mb-3">
                <div class="bg-secondary rounded h-100 p-4">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <h4 class="mb-4"><?= esc($page['title']) ?> Page</h4>
                        <div class="d-flex flex-row align-items-center pb-3">
                            <?php if ($page['edited']) : ?>
                                <form method="post" action="<?= admin_url('html-editor/reset-default') ?>"
                                      onsubmit="return confirm('Are you sure you want to Publish Edited Page?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="slug" value="<?= esc($page['slug']) ?>">
                                    <button type="submit" class="btn btn-outline-warning m-2">Reset</button>
                                </form>
                                <form method="post" action="<?= admin_url('html-editor/publish') ?>"
                                    onsubmit="return confirm('Are you sure you want to Publish Edited Page?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="slug" value="<?= esc($page['slug']) ?>">
                                    <!-- <input type="hidden" name="backup" value=0> -->
                                    <button type="submit" class="btn btn-outline-info m-2">Publish</button>
                                </form>
                            <?php endif ?>
                            <button onclick="window.location.href='<?= admin_url('html-editor/edit/' . $page['slug']) ?>'" type="button" class="btn btn-success m-2">Edit</button>
                        </div>
                    </div>
                    <div class="accordion" id="accordion<?= $page['slug'] ?>">
                        <div class="accordion-item bg-transparent">
                            <h2 class="accordion-header" id="heading<?= esc($page['slug']) ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse<?= esc($page['slug']) ?>" aria-expanded="false"
                                    aria-controls="collapse<?= $page['slug'] ?>">
                                    Backups
                                </button>
                            </h2>
                            <div id="collapse<?= esc($page['slug']) ?>" class="accordion-collapse collapse"
                                aria-labelledby="heading<?= esc($page['slug']) ?>" data-bs-parent="#accordionBackups">
                                <div class="accordion-body">
                                    <div class="bg-secondary rounded h-100">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">File Name</th>
                                                        <!-- <th scope="col">Actions</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (esc($page['backups']) as $i => $backup): ?>
                                                        <tr>
                                                            <th scope="row"><?= $i + 1 ?></th>
                                                            <td>
                                                                <div
                                                                    class="d-flex flex-row align-items-center justify-content-between">
                                                                    <span><?= substr($backup, 0, -4) ?></span>
                                                                    <div class="d-flex flex-row align-items-center">
                                                                        <form method="post" action="<?= admin_url('html-editor/delete') ?>"
                                                                            onsubmit="return confirm('Are you sure you want to delete this Backup?');">
                                                                            <?= csrf_field() ?>
                                                                            <input type="hidden" name="slug" value="<?= esc($page['slug']) ?>">
                                                                            <input type="hidden" name="backup" value="<?= $backup ?>">
                                                                            <button type="submit"
                                                                            class="btn btn-outline-danger me-2">Delete</button>
                                                                        </form>
                                                                        <form method="post" action="<?= admin_url('html-editor/publish') ?>"
                                                                            onsubmit="return confirm('Are you sure you want to Restore this Backup?');">
                                                                            <?= csrf_field() ?>
                                                                            <input type="hidden" name="slug" value="<?= esc($page['slug']) ?>">
                                                                            <input type="hidden" name="backup" value="<?= $backup ?>">
                                                                            <button type="submit"
                                                                            class="btn btn-success ">Restore</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>