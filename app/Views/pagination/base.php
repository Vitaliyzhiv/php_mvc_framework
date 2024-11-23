<nav aria-label="Page navigation example">
    <ul class="pagination">


        <!-- Если  переменная first page  не пуста, выводим ссылку на нее  -->
        <?php if (!empty($first_page)): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $first_page ?>" aria-label="First Page">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Если переменная back не пуста, выводим ссылку на нее  -->
        <?php if (!empty($back)): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $back ?>" aria-label="Previous Page">
                    <span aria-hidden="true">&lt;</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- если массив $pages_left не пустой, выводим страницы слева в цикле -->
        <?php if (!empty($pages_left)): ?>
            <!-- проходимся циклом по массиву и выводим линки -->
            <?php foreach ($pages_left as $page): ?>
                <li class="page-item"><a class="page-link" href="<?= $page['link']; ?>"><?= $page['number']; ?></a></li>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- добавляем active к текущей странице -->
        <li class="page-item active "><a class="page-link"><?= $current_page; ?></a></li>

        <!-- если массив $pages_right не пустой, выводим страницы слева в цикле -->
        <?php if (!empty($pages_right)): ?>
            <!-- проходимся циклом по массиву и выводим линки -->
            <?php foreach ($pages_right as $page): ?>
                <li class="page-item"><a class="page-link" href="<?= $page['link']; ?>"><?= $page['number']; ?></a></li>
            <?php endforeach; ?>
        <?php endif; ?>
        <!-- Если переменная forward не пуста, выводим ссылку на нее  -->
        <?php if (!empty($forward)): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $forward ?>" aria-label="Next Page">
                    <span aria-hidden="true">&gt;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (!empty($last_page)): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $last_page ?>" aria-label="Last Page">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>