<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- мета поле для csrf токена -->
    <?= get_csrf_meta(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP FRAMEWORK :: <?= $title ?? '' ?></title>
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/iziModal/css/iziModal.min.css'); ?>">

    <!-- подключаем стили -->
    <?php if (!empty($styles)) : ?>
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="<?= $style; ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- подкючаем скрипты в хедер -->
    <?php if (!empty($header_scripts)) : ?>
        <?php foreach ($header_scripts as $script): ?>
            <script src="<?= $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</head>

<nav class="navbar navbar-expand-lg bg-dark mb-3" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- выводим меню из кеша -->
            <?= cache()->get('menu'); ?>
        </div>
    </div>
</nav>


<?= get_alerts(); ?>
<?= view()->content; ?>


<script src="<?= base_url('/assets/js/jquery-3.7.1.min.js'); ?>"></script>
<script src="<?= base_url('/assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('/assets/iziModal/js/iziModal.min.js'); ?>"></script>



<!-- подкючаем скрипты в футер -->
<?php if (!empty($footer_scripts)) : ?>
    <?php foreach ($footer_scripts as $script): ?>
        <script src="<?= $script; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<script src="<?= base_url('/assets/js/main.js'); ?>"></script>

<!-- Контейнеры для вывода сообщений алертом с помощью библиотеки  iziModal -->
<div class="iziModal-alert-success">

</div>
<div class="iziModal-alert-error">

</div>


</body>

</html>