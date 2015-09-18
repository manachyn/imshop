<?php

/* @var $this yii\web\View */
/* @var $widget \im\tree\widgets\Tree */
/* @var $items \im\tree\models\Tree[] */
/* @var $level int */
?>

<?php if ($items) : ?>
<ul>
<?php foreach ($items as $item) : ?>
    <li>
        <?= $this->render($widget->itemView, ['model' => $item, 'level' => $level]) ?>
        <?php if (!$widget->depth || $level < $widget->depth) : ?>
            <?= $this->render('tree', ['widget' => $widget, 'items' => $item->children, 'level' => $level + 1]) ?>
        <?php endif ?>
    </li>
<?php endforeach ?>
</ul>
<?php endif ?>