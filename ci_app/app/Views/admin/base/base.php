<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin | <?= $this->renderSection('meta_data') ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="<?= base_url('static/image/adminpanel/favicon.ico') ?>" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?= base_url('static/lib/owlcarousel/assets/owl.carousel.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('static/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') ?>" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?= base_url('static/css/admin-bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="<?= base_url('static/css/style.css') ?>" rel="stylesheet">

    <?= $this->renderSection('head') ?>

    <style>
        <?= $this->renderSection('style') ?>
    </style>

</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">

        <!-- Spinner Start -->
        <?= view_cell('\App\Libraries\AdminSpinner::load') ?>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        <?= view_cell('\App\Libraries\AdminSidebar::load') ?>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <?= $this->renderSection('content') ?>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid pt-4 px-md-4">
        <div class="bg-secondary rounded-top p-4">
            <div class="row">
                <!-- <div class="col-12 col-sm-3 text-center text-sm-start"></div> -->
                <div class=" col-12 text-center">
                    &copy; <a href="<?= base_url() ?>">RAMTIN GANJI POUR</a>, All Right Reserved.
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('static/lib/chart/chart.min.js') ?>"></script>
    <script src="<?= base_url('static/lib/easing/easing.min.js') ?>"></script>
    <script src="<?= base_url('static/lib/waypoints/waypoints.min.js') ?>"></script>
    <script src="<?= base_url('static/lib/owlcarousel/owl.carousel.min.js') ?>"></script>
    <script src="<?= base_url('static/lib/tempusdominus/js/moment.min.js') ?>"></script>
    <script src="<?= base_url('static/lib/tempusdominus/js/moment-timezone.min.js') ?>"></script>
    <script src="<?= base_url('static/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') ?>"></script>

    <!-- Template Javascript -->
    <script src="<?= base_url('static/js/main.js') ?>"></script>
</body>

</html>