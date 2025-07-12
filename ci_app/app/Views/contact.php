<?php $this->extend('base/base') ?>

<?php $this->section('meta_title') ?><?= esc($meta_title) ?><?php $this->endSection() ?>

<?php $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center g-3 mt-2 mt-md-5 py-md-5">
        <div class="col-12 col-md-5 mb-4 d-flex justify-content-center py-1">
            <img src="<?= base_url('static/image/main/contact.jpg') ?>" alt="" class="img-fluid img-thumbnail" />
        </div>
        <div class="col-12 col-md-5 mb-4 d-flex flex-column justify-content-center align-items-center">
            <div class="w-100 w-md-75">
                <code class="text-dark h3 d-block text-center mb-2">CONTACT</code>
                <hr class="w-50 border-1 border-dark opacity-100 mt-0 mb-3">
                <blockquote class="m-0 p-0 quicksand-500 text-center">Iran, Mazandaran, Sari</blockquote>
                <blockquote class="mt-3 quicksand-500 text-center">Email: GanjipourRamtin@Gmail.com</blockquote>
            </div>
            <div class="d-flex justify-content-center gap-4 mt-4">
                <a href="#"><i class="fs-3 fa-brands fa-telegram hover-custom"></i></a>
                <a href="#"><i class="fs-3 fa-brands fa-linkedin-in hover-custom"></i></a>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>