<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= esc($meta_title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">
    <?= view('admin/base/navbar') ?>

    <!-- Edit Project Form -->
    <div class="container-fluid px-2 px-md-4 pt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="bg-secondary rounded p-3 p-md-4">
                    <h6 class="text-center mb-3 mb-md-4">Edit Project Form</h6>
                    <form method="post" action="<?= admin_url('project/edit-project') ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= old('projectID', esc($project['id'])) ?>" />

                        <?php if (session()->has('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" id="title"
                                value="<?= old('title', esc($project['title'])) ?>">
                            <div class="form-text">Write project title; it will display on the project page.</div>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <div class="input-group">
                                <span style="background-color:rgb(51, 52, 54);" class="input-group-text" id="basic-addon3"><?= base_url() ?></span>
                                <input name="slug" type="text" class="form-control" id="slug"
                                    aria-describedby="basic-addon3" value="<?= old('slug', esc($project['slug'])) ?>">
                            </div>
                            <div class="form-text">Used in URL for SEO. No spaces or special chars; separate words with
                                '-'.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label for="programming_language" class="form-label">Programming Language</label>
                                <input name="programming_language" type="text" class="form-control"
                                    id="programming_language"
                                    value="<?= old('programming_language', esc($project['programming_language'])) ?>">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="framework" class="form-label">Framework</label>
                                <input name="framework" type="text" class="form-control" id="framework"
                                    value="<?= old('framework', esc($project['framework'])) ?>">
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="description"
                                rows="6"><?= old('description', esc($project['description'])) ?></textarea>
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