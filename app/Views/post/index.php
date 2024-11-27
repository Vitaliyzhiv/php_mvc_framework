<div class="container">
    <!-- выводим пользователей циклом -->
    <ul>
        <?php foreach ($posts as $post) : ?>
            <li>
                <a href="<?= base_href("/post/{$post['slug']}"); ?>">
                    <?= $post['title']; ?>
                </a>
            </li>

        <?php endforeach; ?>
    </ul>

    <br>

</div>