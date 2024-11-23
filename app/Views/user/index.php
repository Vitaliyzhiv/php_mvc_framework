<div class="container">
    <!-- выводим пользователей циклом -->
    <?php foreach ($users as $user) : ?>
        <?= $user['name']; ?><br>
        <? //= $user['email']; 
        ?>
    <?php endforeach; ?>
    <br>
    <!-- выводим pagination  -->
    <?= $pagination; ?>
</div>