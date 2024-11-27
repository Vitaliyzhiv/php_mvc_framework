<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Добавляем этот мета тег чтобы пути  к картинкам и так же к страницам пагинации формировались правильно -->
    <base href="<?= base_url('/'); ?>">
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
        <a class="navbar-brand" href="<?= base_href(); ?>"><?php _e('tpl_logo')?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- выводим меню из кеша -->
            <?= cache()->get('menu'); ?>

            <?php $request_uri = uri_without_lang();  ?>
            <div class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php $current_lang = app()->get('lang')['title'] ?>
                        <?= $current_lang; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php foreach (LANGS as $key => $value): ?>
                            <?php if ($value['title'] != $current_lang): ?>
                                <?php if ($value['base'] == 1): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url("{$request_uri}"); ?>"><?= $value['title']; ?></a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url("/{$key}{$request_uri}"); ?>"><?= $value['title']; ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </div>
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