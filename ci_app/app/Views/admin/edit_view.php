<?= $this->extend('admin/base/base') ?>

<?= $this->section('meta_title') ?>
<?= esc($meta_title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">
    <?= view('admin/base/navbar') ?>

    <!-- Admin Projects List -->
    <div class="container-fluid px-2 px-md-4 pt-4">
        <div class="col-sm-12 mb-3">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="d-flex flex-row align-items-center justify-content-between">
                    <h4 class="mb-4"><?= $slug ?> Page</h4>
                    <div class="d-flex flex-row align-items-center pb-3">
                        <form method="post" action="<?= admin_url('html-editor/reset-default') ?>"
                            onsubmit="return confirm('Are you sure you want to Publish Edited Page?');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="slug" value="<?= $slug ?>">
                            <button type="button" class="btn btn-outline-info m-2">Make From Default Page</button>
                        </form>
                        <form id="previewform" action="<?= admin_url('html-editor/preview/' . $slug) ?>" method="post"
                            target="_blank">
                            <?= csrf_field() ?>
                            <input type="hidden" name="content" id="hiddenInput">
                            <button type="submit" class="btn btn-success m-2">Preview</button>
                        </form>
                    </div>
                </div>
                <form class="form-floating" method="post" action="<?= admin_url('html-editor/save') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="slug" value="<?= $slug ?>">
                    <textarea oninput="autoResize(this)" class="form-control"
                        placeholder="File Content Should Be Showed In Here....Something Went Wrong if This TextArea is Empty!"
                        id="contentTextarea" name="content"
                        style="height: auto;overflow: hidden;"><?= esc($content) ?></textarea>
                    <button type="submit" class="btn btn-success mt-3 w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function autoResize(textarea) {
        textarea.style.height = 'auto'; // Reset height
        textarea.style.height = textarea.scrollHeight + 'px'; // Set to scroll height
    }

    // Optional: auto-size on page load
    window.addEventListener('load', () => {
        document.querySelectorAll('textarea').forEach(autoResize);
    });
</script>

<script>
    document.getElementById('previewform').addEventListener('submit', function (e) {
        // Sync textarea value to hidden input before submitting
        const content = document.getElementById('contentTextarea').value;
        document.getElementById('hiddenInput').value = content;
    });
</script>

<?= $this->endSection() ?>