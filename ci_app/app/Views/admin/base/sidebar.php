<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-secondary navbar-dark">
        <a href="<?= admin_url('dashboard') ?>" class="navbar-brand mx-4 mb-3">
            <h3 class="text-* text-white">Admin Panel</h3>
        </a>
        <div class="navbar-nav w-100">
            <a href="<?= admin_url('dashboard') ?>"
                class="nav-item nav-link mb-3 <?= $active == 'dashboard' ? 'active' : '' ?>"><i
                    class="fa fa-tachometer-alt me-2"></i>Dashboard</a>



            <div class="nav-item dropdown mb-3">
                <a href="<?= admin_url('project') ?>"
                    class="nav-link dropdown-toggle <?= $active == 'gallery' ? 'active' : '' ?>"
                    data-bs-toggle="dropdown"><i class="fa fa-th me-2"></i>Gallery</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="<?= admin_url('gallery') ?>" class="dropdown-item">Galleries</a>
                    <a href="<?= admin_url('gallery/new-gallery') ?>" class="dropdown-item">New Gallery</a>
                    <a href="<?= admin_url('gallery/trash') ?>" class="dropdown-item">Trash</a>
                </div>
            </div>


            <div class="nav-item dropdown mb-3">
                <a href="<?= admin_url('project') ?>"
                    class="nav-link dropdown-toggle <?= $active == 'projects' ? 'active' : '' ?>"
                    data-bs-toggle="dropdown"><i class="fa fa-th me-2"></i>Projects</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="<?= admin_url('project') ?>" class="dropdown-item">Projects</a>
                    <a href="<?= admin_url('project/new-project') ?>" class="dropdown-item">New Project</a>
                    <a href="<?= admin_url('project/trash') ?>" class="dropdown-item">Trash</a>
                </div>
            </div>


            <a href="<?= admin_url('setting') ?>"
                class="nav-item nav-link mb-3 <?= $active == 'setting' ? 'active' : '' ?>"><i
                    class="fa fa-laptop me-2"></i>Setting</a>

            <!-- <a href="<?= admin_url('logout') ?>"
                class="nav-item nav-link mb-3 d-flex align-items-center" style="transition: background-color 0.1s ease-in;" onmouseover="this.style.backgroundColor='red'"
    onmouseout="this.style.backgroundColor='transparent'">
                <i class="far fa-file-alt me-2"></i>Logout
            </a> -->

        </div>
    </nav>
</div>
<!-- Sidebar End -->