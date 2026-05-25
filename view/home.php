<?php 

require BASE_PATH . '/view/layout/header.php';

require_once BASE_PATH . '/inc/CustomPath.php'; 

use View\ItemView;
?>


<div class="wrapper">
    <h2 class="title">May we suggest something?</h2>

    <ul class="catalog">
        <?php foreach ($random as $item): ?>
            <?= ItemView::render($item); ?>
        <?php endforeach; ?>
    </ul>
</div>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>
