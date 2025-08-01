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
                    <textarea id="contentTextarea" oninput="autoResize(this)" class="form-control"
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

    <script>
        // Key for localStorage
        const STORAGE_KEY = 'editor_draft_<?= esc($slug) ?>';

        // Utility: save current textarea
        function saveDraft() {
            const txt = document.getElementById('contentTextarea').value;
            localStorage.setItem(STORAGE_KEY, txt);
        }

        // Utility: restore on load
        function restoreDraft() {
            const draft = localStorage.getItem(STORAGE_KEY);
            if (draft !== null) {
                document.getElementById('contentTextarea').value = draft;
                // auto-resize if you have that function
                autoResize(document.getElementById('contentTextarea'));
                // optionally clear so you don’t keep restoring on future visits:
                // localStorage.removeItem(STORAGE_KEY);
            }
        }

        // On page load, restore any saved draft
        window.addEventListener('DOMContentLoaded', restoreDraft);

        // For *preview* form:
        document.getElementById('previewform').addEventListener('submit', function(e) {
            saveDraft();
            // let it submit & open new tab...
            // then reload this page so CSRF fields get fresh tokens:
            setTimeout(() => location.reload(), 500);
        });
    </script>


<?= $this->endSection() ?>