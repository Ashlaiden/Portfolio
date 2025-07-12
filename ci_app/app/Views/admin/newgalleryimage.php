<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= esc($meta_title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">
    <?= view('admin/base/navbar') ?>

    <!-- Add Gallery Image Form -->
    <div class="container-fluid px-2 px-md-4 pt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="bg-secondary rounded p-3 p-md-4">
                    <h6 class="text-center mb-3 mb-md-4">Add Gallery Image</h6>
                    <form method="post" action="<?= esc($submit) ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="project_id" value="<?= esc($project_id) ?>">

                        <?php if (session()->has('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="row g-3 align-items-center">
                            <label for="title" class="col-12 col-sm-4 col-form-label mb-0">Title of Image</label>
                            <div class="col-12 col-sm-8">
                                <input name="title" id="title" type="text" class="form-control"
                                    value="<?= old('title') ?>" placeholder="Title of Gallery">
                            </div>
                            <div class="form-text mt-2">Write Title, This gonna use in URL Path of Images For share
                                Image.<br>It's Better To Choose a Unique Name.</div>

                            <label for="gallery_image" class="col-12 col-sm-4 col-form-label mb-0">Choose Gallery
                                Image</label>
                            <div class="col-12 col-sm-8">
                                <input name="gallery_image" id="gallery_image" type="file" class="form-control bg-dark"
                                    accept="image/*">
                            </div>
                            <div class="form-text mt-2">Choose an image to add into the project gallery.</div>


                            <label for="project_Select" class="col-12 col-sm-4 col-form-label mb-0">Choose Project to
                                Attach</label>
                            <div class="col-12 col-sm-8">
                                <div class="form-floating mb-3">
                                    <select name="project_select" class="form-select" id="projectSelect"
                                        aria-label="Floating label select example">
                                        <option value="none" <?= $project_id == null ? 'selected' : '' ?>>No Project</option>
                                        <?php foreach ($projects as $project): ?>
                                            <option value="<?= esc($project['id']) ?>" <?= $project_id == $project['id'] ? 'selected' : '' ?>><?= esc($project['title']) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                    <label for="floatingSelect">Select Project or Set to [No-Project]</label>
                                </div>
                            </div>
                            <div class="form-text mt-2">Select Project You want to add this Image to That.</div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success w-100">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>