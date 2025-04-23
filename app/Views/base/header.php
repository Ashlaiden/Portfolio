<header>
<nav class="navbar navbar-expand-md navbar-light bg-light">
  <div class="position-relative container-fluid">
    <!-- Mobile toggler -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
    </button>

    <!-- Navigation links -->
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link fs-4 <?= $active_nav == 'home' ? 'active text-success' : 'hover-custom' ?>" href="<?= base_url('/') ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fs-4 <?= $active_nav == 'about' ? 'active text-success' : 'hover-custom' ?>" href="<?= base_url('/about') ?>">About Me</a>
        </li>
        <?php if ($have_projects): ?>
        <li class="nav-item">
          <a class="nav-link fs-4 <?= $active_nav == 'projects' ? 'active text-success' : 'hover-custom' ?>" href="<?= base_url('/projects') ?>">Projects</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link fs-4 <?= $active_nav == 'contact' ? 'active text-success' : 'hover-custom' ?>" href="<?= base_url('/contact') ?>">Contact</a>
        </li>
      </ul>
    </div>

    <!-- Admin Panel button stays visible -->
    <?php if (esc($isAdmin) == true) : ?>
      <a class="position-absolute top-0 end-0 me-4 btn btn-dark text-white ms-auto ms-md-3 mt-0 mt-md-2" href="<?= admin_url() ?>">Admin Panel</a>
    <?php endif ?>
  </div>
</nav>
<hr class="border-1 border-dark opacity-100 mb-5">
</header>
