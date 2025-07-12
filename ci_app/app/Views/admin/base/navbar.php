<!-- Navbar Start -->
<nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
    <a href="#" class="sidebar-toggler flex-shrink-0">
        <i class="fa fa-bars"></i>
    </a>
    <div class="navbar-nav align-items-center ms-auto">
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <!-- <img class="rounded-circle me-lg-2" src="<?= base_url('static/image/adminpanel/user.jpg') ?>" alt="" style="width: 40px; height: 40px;"> -->
                <span class="d-none d-lg-inline-flex">Menu</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-secondary border-3 rounded-0 rounded-bottom m-0">
                <a href="<?= base_url() ?>" class="dropdown-item">Home Page</a>
                <!-- <a href="<?= admin_url('setting') ?>" class="dropdown-item">Settings</a> -->
                <a href="<?= admin_url('logout') ?>" class="dropdown-item">Log Out</a>
            </div>
        </div>
    </div>
</nav>
<!-- Navbar End -->