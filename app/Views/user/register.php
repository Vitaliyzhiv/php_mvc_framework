<div class="container">

    <h1><?= $title ?? ''; ?></h1>

    <!-- дамп csrf_token-->
    <?php //dump(session()->get('csrf_token')); 
    ?>

    <div class="row">
        <div class="col-md-6 offset-md-3">

            <form action="<?= base_href('/register'); ?>" method="post" class="ajax-form">

                <!-- вызываем функцию helper getCsrfField  для добавления поля csrf_token -->
                <?= get_csrf_field(); ?>
                <!-- name -->
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <?php _e('user_register_name'); ?>
                    </label>
                    <!-- с помощью функции хелпера old сохраняем заполненные значения -->
                    <input type="text" name="name" class="form-control <?= get_validation_class('name'); ?>" value="<?= old('name'); ?>" id="name"
                        placeholder="<?php _e('user_register_name'); ?>">
                    <!-- выводим ошибку для поля с помощью helper get_errors -->
                    <?= get_errors('name'); ?>
                </div>
                <!-- email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <?php _e('user_register_email'); ?>
                    </label>
                    <input type="email" name="email" value="<?= old('email'); ?>" class="form-control <?= get_validation_class('email'); ?>" id="email" placeholder="name@example.com">
                    <!-- выводим ошибку для поля с помощью helper get_errors -->
                    <?= get_errors('email'); ?>
                </div>
                <!-- password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <?php _e('user_register_password'); ?>
                    </label>
                    <input type="password" name="password" class="form-control <?= get_validation_class('password'); ?>" id="password"
                        placeholder="<?php _e('user_register_password'); ?>">
                    <?= get_errors('password'); ?>
                </div>
                <!-- password confirm -->
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">
                        <?php _e('user_register_confirmPassword'); ?>
                    </label>
                    <input type="password" name="confirmPassword" class="form-control <?= get_validation_class('confirmPassword'); ?>" id="confirmPassword"
                        placeholder="<?php _e('user_register_confirmPassword'); ?>">
                    <?= get_errors('confirmPassword'); ?>
                </div>
                <!-- submit button -->
                <button type="submit" class="btn btn-warning">
                    <?php _e('user_register_button'); ?>
                </button>
            </form>

            <!-- с помощью метода remove класса Session убираем вывод ошибок -->
            <?= session()->remove('form_errors'); ?>
            <!-- с помощью метода remove класса Session убираем вывод данных -->
            <?= session()->remove('form_data'); ?>
        </div>
    </div>

</div>