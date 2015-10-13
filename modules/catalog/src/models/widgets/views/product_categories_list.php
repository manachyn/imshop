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
    <nav class="product-categories-tree">
        <?= $tree ?>
    </nav>
<?php endif ?>