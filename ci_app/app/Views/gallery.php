<?= $this->extend('base/base') ?>

<?= $this->section('meta_title') ?><?= esc($meta_title) ?><?= $this->endsection() ?>

<?= $this->section('style') ?>

/* gallery */

.masonry-gallery {
column-count: 2;
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
column-count: 2;
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />


<main class="container mb-3 py-0 py-md-3">
    <h1 class="text-center h3 fs-1 mb-1">
        <code class="text-dark display-6">Gallery</code>
    </h1>
    <hr class="border-1 border-dark opacity-100 mb-0 mb-md-5">


    <section class="portfolio" id="portfolio">
        <div class="container-fluid px-0">
            <div class="masonry-gallery">

                <?php foreach (esc($galleries) as $image): ?>
                    <a href="<?= base_url($image['image_path']) ?>" data-fancybox="gallery"
                        data-caption="<?= esc($image['title'] . '-d' . $image['id']) ?>"
                        data-slug="<?= strtolower(preg_replace('/[^a-z0-9]+/', '-', esc($image['title'] . '-d' . $image['id']))) ?>">
                        <img src="<?= base_url($image['image_path']) ?>" alt="<?= esc($image['title']) ?>" />
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
    function addMasonryFillers() {
        const container = document.querySelector('.masonry-gallery');
        const columnCount = getComputedStyle(container).getPropertyValue('column-count');
        const children = Array.from(container.children);

        // Wait for images to load for accurate height
        let imagesLoaded = 0;
        children.forEach(child => {
            const img = child.querySelector('img');
            if (img.complete) imagesLoaded++;
            else img.addEventListener('load', () => {
                imagesLoaded++;
                if (imagesLoaded === children.length) addFillers();
            });
        });
        if (imagesLoaded === children.length) addFillers();

        function addFillers() {
            // Get column heights
            const columns = Array.from({ length: columnCount }, () => 0);
            children.forEach((child, index) => {
                // Estimate column by index modulo columns count
                const colIndex = index % columnCount;
                columns[colIndex] += child.offsetHeight + parseInt(getComputedStyle(container).columnGap) || 0;
            });

            // Find max column height
            const maxHeight = Math.max(...columns);

            // For each column, add filler div to equalize height
            columns.forEach((colHeight, colIndex) => {
                const diff = maxHeight - colHeight;
                if (diff > 0) {
                    const filler = document.createElement('div');
                    filler.style.height = diff + 'px';
                    filler.style.breakInside = 'avoid';
                    filler.style.width = '100%';
                    filler.style.margin = '0';
                    filler.style.padding = '0';
                    filler.style.visibility = 'hidden';
                    container.appendChild(filler);
                }
            });
        }
    }

    // Run after DOM and images load
    window.addEventListener('load', addMasonryFillers);

</script>

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
        // Fancybox.bind('[data-fancybox="gallery"]', {
        //     animated: true,
        //     showClass: "fancybox-zoomInDown",
        //     hideClass: "fancybox-fadeOut",
        //     dragToClose: true,

        //     Toolbar: {
        //         display: [
        //             "close",
        //             "counter",
        //             "zoom",
        //             "slideshow",
        //             "fullscreen",
        //             "download",
        //             "thumbs",
        //             "share"
        //         ]
        //     },

        //     // Disable built-in hash plugin completely
        //     Hash: false,

        //     on: {
        //         reveal: (fancybox, slide) => {
        //             const el = slide.triggerEl;
        //             if (el && el.dataset.slug) {
        //                 const currentSlug = decodeURIComponent(window.location.hash.slice(1));
        //                 const newSlug = el.dataset.slug;

        //                 if (currentSlug !== newSlug) {
        //                     history.replaceState(null, '', '#' + newSlug);
        //                 }
        //             }
        //         },

        //         closing: () => {
        //             history.replaceState(null, '', window.location.pathname + window.location.search);
        //         }
        //     }
        // });

    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        Fancybox.bind('[data-fancybox="gallery"]', {
            Hash: true, // Disable Fancybox’s default hash plugin

            animated: true,
            showClass: "fancybox-zoomInDown",
            hideClass: "fancybox-fadeOut",
            dragToClose: true,

            Toolbar: {
                display: [
                    "close",
                    "counter",
                    "zoom",
                    "slideshow",
                    "fullscreen",
                    "download",
                    "thumbs",
                    "share"
                ]
            },

            on: {
                reveal: (fancybox, slide) => {
                    const el = slide.triggerEl;
                    if (el && el.dataset.slug) {
                        const slug = el.dataset.slug;
                        const currentHash = decodeURIComponent(location.hash.slice(1));
                        if (currentHash !== slug) {
                            history.replaceState(null, '', '#' + slug);
                        }
                    }
                },
                closing: () => {
                    history.replaceState(null, '', location.pathname + location.search);
                }
            }
        });
    });
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>

<?= $this->endsection() ?>