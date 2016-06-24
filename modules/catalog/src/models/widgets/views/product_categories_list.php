<?php

use im\tree\widgets\Tree;

/* @var $this yii\web\View */
/* @var $widget \im\catalog\models\widgets\ProductCategoriesList */
/* @var $items \im\tree\models\Tree[] */
?>

<?php if ($tree = Tree::widget([
    'items' => $items,
    'itemView' => '@im/catalog/views/product-category/_tree_item',
    'depth' => $widget->depth
])) : ?>
    <section class="widget product-categories-list-widget">
        <?php if ($widget->title) : ?>
            <h2 class="widget-title"><?= $widget->title ?></h2>
        <?php endif ?>
        <nav class="product-categories-tree">
            <?= $tree ?>
        </nav>
    </section>
<?php endif ?>


