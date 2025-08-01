<?= $this->extend('base/base') ?>

<?= $this->section('meta_title') ?><?= esc($meta_title) ?><?= $this->endsection() ?>

<?php $this->section('content') ?>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 p-2 shadow-lg rounded">
            <!-- <img class="img-fluid" src="<?= base_url("static/image/home.jpg") ?>" alt=""> -->
            <div class="bg-image hover-overlay">
                <img src="<?= base_url("static/image/main/home.jpg") ?>" alt="Programming is an Art Form!"
                    class="img-fluid w-100" />
                <div class="mask" style="
              background: linear-gradient(
                  45deg,
                  hsla(168, 85%, 52%, 0.5),
                  hsla(263, 88%, 45%, 0.5) 100%
              );"></div>
            </div>
        </div>
        <div class="col-12">
            <h1 class="text-center mt-4 display-4">
                <code class="text-dark">Test TimeZone</code>
            </h1>
            <hr class="border-1 border-dark opacity-100">
            <div class="text-center">
                <code class="text-dark h5">Computer Engineering Student</code>
            </div>
        </div>
    </div>
</main>
<hr class="border-1 border-dark opacity-100">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 mb-2 text-center">
            <blockquote>
                <span class="fw-bold">Address: </span>
                Iran, Mazandaran, Sari
            </blockquote>
        </div>
        <div class="col-12 col-md-6 mb-2 text-center">
            <blockquote>
                <span class="fw-bold">Email: </span>
                ganjipourramin@gmail.com
            </blockquote>
        </div>
    </div>
    <div class="row justify-content-center mt-4 mb-5">
        <div class="col-auto">
            <a href=""><i class="fs-3 fa-brands fa-telegram hover-custom"></i></a>
        </div>
        <div class="col-auto">
            <a href=""><i class="fs-3 fa-brands fa-linkedin-in hover-custom"></i></a>
        </div>
    </div>
</div>

<?php $this->endSection() ?>