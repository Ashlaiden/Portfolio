<?= $this->extend('base/main')?>

<?= $this->section('meta_title')?><?php esc($meta_title) ?><?= $this->endSection()?>


<?= $this->section('content')?>

<h1>Admin Dashboard</h1>

<?= view_cell('\App\Libraries\Footer::load') ?>
<?= $this->endSection()?>



