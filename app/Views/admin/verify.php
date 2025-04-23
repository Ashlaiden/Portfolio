<?= $this->extend('base/main') ?>

<?= $this->section('meta_title') ?><?php esc($meta_title) ?><?= $this->endSection() ?>


<?= $this->section('style') ?>
.gradient-custom {
/* fallback for old browsers */
background: #4a4a4a;

/* Chrome 10-25, Safari 5.1-6 */
background: -webkit-linear-gradient(to right, #4a4a4a, #1e1e1e);

/* Modern browsers */
background: linear-gradient(to right, #4a4a4a, #1e1e1e);
}


<!--.gradient-custom {-->
<!--/* fallback for old browsers */-->
<!--background: #6a11cb;-->
<!---->
<!--/* Chrome 10-25, Safari 5.1-6 */-->
<!--background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));-->
<!---->
<!--/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */-->
<!--background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1))-->
<!--}-->
<?= $this->endSection() ?>

<?= $this->section('container_class') ?>
col-12
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="min-vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">

                        <form method="post" action="<?= admin_url('login/verify') ?>
" class="mb-md-5 mt-md-4 pb-5">

                            <?= csrf_field() ?>

                            <h2 class="fw-bold mb-2 text-uppercase">Verify Email</h2>
                            <p class="text-white-50 mb-5">Please Enter Code We Sent to Your Email Address.</p>

                            <div data-mdb-input-init class="form-outline form-white mb-4">
                                <input name="code" type="text" id="typeEmailX" class="form-control form-control-lg" />
                                <label class="form-label" for="typeEmailX">Verification Code</label>
                            </div>

                            <?php if (session()->has('errors')): ?>
                                <div class="text-danger my-3 text-start">
                                    <ul>
                                        <?php foreach (session('errors') as $error): ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <!-- <p id="resendBtn" class="small mb-5 pb-lg-2 disabled" style="cursor: pointer;">Resend Code (<span class="text-white-50" id="timer">120</span>s)</p> -->
                            <button class="focus-none text-primary small btn mb-5 pb-lg-2" id="resendBtn"
                                style="box-shadow: none;" disabled>Resend Code (<span class="text-white-50"
                                    id="timer"><?= esc($email_interval) ?></span>s)</button>
                            <div id="statusMsg"></div>

                            <p id="statusmsg" class=""></p>

                            <button id="verifybtn" data-mdb-button-init data-mdb-ripple-init
                                class="btn btn-outline-light btn-lg px-5" type="submit">Verify</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="d-block w-100 text-center bg-dark text-white">
    <?= view_cell('\App\Libraries\Footer::load') ?>
</div>

<script>
    const btn = document.getElementById('resendBtn');
    const timerSpan = document.getElementById('timer');
    function initResendTimer(codeSentAt) {
        const cooldown = <?= esc($email_interval) ?>;

        let interval; // ⬅️ Declare interval at the top

        function updateTimer() {
            const now = Math.floor(Date.now() / 1000);
            const elapsed = now - codeSentAt;
            const remaining = cooldown - elapsed;

            if (remaining <= 0) {
                btn.disabled = false;
                btn.innerText = 'Resend Code';
                timerSpan.textContent = '';
                clearInterval(interval); // ✅ Now it exists
            } else {
                btn.disabled = true;
                btn.innerText = `Resend Code (${remaining}s)`;
                timerSpan.textContent = remaining;
            }
        }

        updateTimer();
        interval = setInterval(updateTimer, 1000); // ⬅️ Assigned after it's safe to reference
    }

    btn.addEventListener('click', function () {
        const statusMsg = document.getElementById('statusmsg');
        btn.disabled = true;
        btn.innerText = 'Resending...';
        statusMsg.textContent = '';
        const url = '<?= base_url('/admin/login/verify/resend-code') ?>';

        axios.get(url)
            .then(response => {
                // 1) Grab the JSON payload
                const data = response.data;                               // 📦 axios makes parsed JSON here :contentReference[oaicite:3]{index=3}

                // 2) Reset status message styling
                statusMsg.className = '';

                if (data.success) {
                    statusMsg.classList.add('text-success');                // ✅ correct classList usage :contentReference[oaicite:4]{index=4}
                    statusMsg.textContent = 'New code sent!';
                    codeSentAt = Math.floor(Date.now() / 1000);
                    initResendTimer(data.code_time);                        // now 'data' is defined
                }

                setTimeout(() => {
                    statusMsg.className = '';
                    statusMsg.textContent = '';
                }, 10000);
            })
            .catch(error => {
                // Now this only fires on real network or HTTP errors
                console.error(error);
                statusMsg.className = '';
                statusMsg.classList.add('text-danger');
                statusMsg.textContent = 'Network error';
                btn.disabled = false;
                btn.innerText = 'Try Again';
                setTimeout(() => {
                    statusMsg.className = '';
                    statusMsg.textContent = '';
                }, 10000);
            });
    });

    // Call this with your server-sent timestamp:
    initResendTimer(Math.floor(<?= esc($code_time) ?>));
</script>
<?= $this->endSection() ?>