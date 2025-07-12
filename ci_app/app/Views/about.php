<?php $this->extend('base/base') ?>

<?php $this->section('meta_title') ?><?= esc($meta_title) ?><?php $this->endSection() ?>

<?php $this->section('content') ?>

<div class="container">
    <h3 class="text-center">
        <code class="text-dark">Computer Engineering Student</code>
    </h3>
    <hr class="border-1 border-dark opacity-100 mb-5">

    <div class="row mb-3">
        <div class="col-12 col-md-6 d-flex justify-content-center py-1">
            <img src="<?= base_url('static/image/main/profile.png') ?>" alt="" class="img-fluid" style="width: 70%;">
        </div>
        <div class="col-12 col-md-6 d-flex flex-column justify-content-center">
            <div class="w-100 w-md-50">
                <code class="text-dark py-0 px-auto m-0 h3">Ramtin Ganji Pour</code>
                <hr class="border-1 border-dark opacity-100 mt-0 mb-2">
                <blockquote class="m-0 p-0 quicksand-500"><span>Born in: </span>Sari, Iran</blockquote>
                <blockquote class="m-0 p-0 quicksand-500"><span>Live & Study in </span>Sari, Iran</blockquote>
                <blockquote class="m-0 p-0 quicksand-500">GanjipourRamtin@Gmail.com</blockquote>
            </div>
        </div>
    </div>

    <div class="row g-1 mt-3 px-2 px-md-2">
        <div class="col-12 col-md-5 mb-4">
            <div class="px-4">
                <div class="quicksand-600 h3 m-0 p-0">Education</div>
                <hr class="w-75 border-1 border-dark opacity-100 mb-1 mt-0 pt-0">
                <span class="text-dark quicksand-500 fs-6 d-flex align-items-center"><i class="fa-solid fa-circle me-2"
                        style="font-size: 0.3rem;"></i>Rouzbahan University</span>
                <p class="text-dark quicksand-400 fs-6 text-secondary m-0">Bachelor's degree, Computer Engineering</p>
                <p class="text-dark quicksand-400 fs-6 m-0">Oct 2022 - Jan 2026</p>
            </div>
        </div>
        <div class="col-12 col-md-5 mb-4">
            <div class="px-3">
                <div class="h3 quicksand-600 m-0 p-0">Programming Languages</div>
                <hr class="w-75 border-1 border-dark opacity-100 mb-1 mt-0 pt-0">
                <div class="d-flex flex-column justify-content-start ps-2">
                    <span class="text-dark quicksand-400 fs-6 d-flex align-items-center me-3 mb-1"><i
                            class="fa-solid fa-circle me-2" style="font-size:0.3rem;"></i>Python</span>
                    <span class="text-dark quicksand-400 fs-6 d-flex align-items-center me-3 mb-1"><i
                            class="fa-solid fa-circle me-2" style="font-size:0.3rem;"></i>C#</span>
                    <span class="text-dark quicksand-400 fs-6 d-flex align-items-center me-3 mb-1"><i
                            class="fa-solid fa-circle me-2" style="font-size:0.3rem;"></i>JS</span>
                    <span class="text-dark quicksand-400 fs-6 d-flex align-items-center me-3 mb-1"><i
                            class="fa-solid fa-circle me-2" style="font-size:0.3rem;"></i>PHP</span>
                    <span class="text-dark quicksand-400 fs-6 d-flex align-items-center me-3 mb-1"><i
                            class="fa-solid fa-circle me-2" style="font-size:0.3rem;"></i>Basic SQL</span>
                    <span class="text-dark quicksand-400 fs-6 d-flex align-items-center me-3 mb-1"><i
                            class="fa-solid fa-circle me-2" style="font-size:0.3rem;"></i>HTML / CSS</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5 mb-4">
            <div class="px-3">
                <div class="h3 quicksand-600 m-0 p-0">Frameworks</div>
                <hr class="w-75 border-1 border-dark opacity-100 mb-1 mt-0 pt-0">
                <span class="text-dark quicksand-400 fs-6 d-flex align-items-center mb-1"><i
                        class="fa-solid fa-circle me-2" style="font-size:0.3rem;"></i>DJANGO</span>
                <span class="text-dark quicksand-400 fs-6 d-flex align-items-center mb-1"><i
                        class="fa-solid fa-circle me-2" style="font-size:0.3rem;"></i>CodeIgniter</span>
                <span class="text-dark quicksand-400 fs-6 d-flex align-items-center mb-1"><i
                        class="fa-solid fa-circle me-2" style="font-size:0.3rem;"></i>Bootstrap</span>
            </div>
        </div>
    </div>
</div>

<div class="my-5"></div>

<?php $this->endSection() ?>