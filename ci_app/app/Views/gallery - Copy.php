<?= $this->extend('base/base') ?>

<?= $this->section('meta_title') ?><?= esc($meta_title) ?><?= $this->endsection() ?>

<?= $this->section('style') ?>

/* gallery */

.masonry-gallery {
column-count: 3;
column-gap: 25px; /* small space between columns */
}

.masonry-gallery a {
break-inside: avoid;
display: block;
margin-bottom: 8px; /* small vertical space between images */
}

.masonry-gallery img {
width: 100%;
height: auto;
display: block;
border-radius: 4px; /* optional: soften corners */
}
@media (max-width: 1200px) {
.masonry-gallery {
column-count: 3;
}
}
@media (max-width: 768px) {
.masonry-gallery {
column-count: 2;
}
}
@media (max-width: 576px) {
.masonry-gallery {
column-count: 1;
}
}

/* end gallery */

<?= $this->endsection() ?>

<?php $this->section('content') ?>

<main class="container mb-3 py-0 py-md-3">
    <h1 class="text-center h3 fs-1 mb-1">
        <code class="text-dark display-6">Gallery</code>
    </h1>
    <hr class="border-1 border-dark opacity-100 mb-0 mb-md-5">


    <section class="portfolio" id="portfolio">
        <div class="container-fluid px-0">
            <div class="masonry-gallery">

                <?php foreach (esc($galleries) as $image): ?>
                    <a data-fancybox="gallery" href="<?= base_url($image['image_path']) ?>">
                        <img src="<?= base_url($image['image_path']) ?>" alt="<?= $image['title'] ?>" class="img-fluid" />
                    </a>

                <?php endforeach ?>

            </div>
        </div>
    </section>

</main>

<?php $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Make sure jQuery is loaded -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Fancybox (v3+) CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<script>
    $(document).ready(function () {

        // Filter buttons
        $(".filter-button").click(function () {
            var value = $(this).attr('data-filter');

            if (value == "all") {
                $('.filter').show(300);
            } else {
                $(".filter").not('.' + value).hide(300);
                $('.filter').filter('.' + value).show(300);
            }

            // Toggle active class properly
            $(".filter-button").removeClass("active");
            $(this).addClass("active");
        });

        // Fancybox initialization (v3+ syntax)
        Fancybox.bind("[data-fancybox]", {
            // Options (optional)
            Thumbs: false,
            Toolbar: false,
        });

    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>

<?= $this->endsection() ?>