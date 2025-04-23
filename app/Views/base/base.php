<?= $this->extend('base/main')?>

<?= $this->section('meta_title')?>
    <?= $this->renderSection('meta_title')?>
<?= $this->endSection()?>

<?= $this->section('head')?>
<?= $this->renderSection('head')?>
<?= $this->endSection()?>

<?= $this->section('style')?>
<?= $this->renderSection('style')?>
<?= $this->endSection()?>


<?= $this->section('content')?>
<?= view_cell('\App\Libraries\Header::load') ?>

<div class="container col-12 col-sm-10 d-flex flex-column">
    <?= $this->renderSection('content')?>
</div>

<?= view_cell('\App\Libraries\Footer::load') ?>
<?= $this->endSection()?>



