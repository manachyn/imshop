<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget \im\tree\widgets\Tree */
/* @var $items \im\tree\models\Tree[] */
/* @var $level int */

$options = $level == 1 ? $widget->options : [];
?>

<?php if ($items) : ?>
<?= Html::beginTag('ul', $options) ?>
<?php foreach ($items as $item) : ?>
    <?php if (!$widget->itemVisibility || ($widget->itemVisibility instanceof \Closure && call_user_func($widget->itemVisibility, $item))) : ?>
        <?= Html::beginTag('li', $widget->itemOptions) ?>
        <?= $this->render($widget->itemView, array_merge(['model' => $item, 'level' => $level], $widget->itemViewParams)) ?>
        <?php if (!$widget->depth || $level < $widget->depth) : ?>
            <?= $this->render('tree', ['widget' => $widget, 'items' => $item->children, 'level' => $level + 1]) ?>
        <?php endif ?>
        <?= Html::endTag('li') ?>
    <?php endif ?>
<?php endforeach ?>
<?= Html::endTag('ul') ?>
<?php endif ?>