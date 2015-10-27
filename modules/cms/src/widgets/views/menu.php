<?php

use im\cms\models\MenuItem;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $items \im\cms\models\MenuItem[] */
/* @var $parent \im\cms\models\MenuItem */
/* @var $level int */

$parent = isset($parent) ? $parent : null;
$tag = 'li';
if ($parent) {
    switch ($parent->items_display) {
        case MenuItem::DISPLAY_DROPDOWN:
            $tag = 'li';
            break;
        case MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN:
            $tag = 'li';
            break;
        case MenuItem::DISPLAY_GRID:
            $tag = 'div';
            break;
    }
}
?>

<?php if ($items) : ?>
<?= $this->render('menu_items_start', ['parent' => $parent, 'widget' => $widget, 'level' => $level]) ?>
<?php foreach ($items as $item) : ?>
    <?php if ($item->isVisible()) :
        $itemOptions = $item->getHtmlAttributes();
        switch ($item->items_display) {
            case MenuItem::DISPLAY_DROPDOWN:
                Html::addCssClass($itemOptions, 'dropdown');
                break;
            case MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN:
                Html::addCssClass($itemOptions, 'dropdown dropdown-full-width');
                break;
            case MenuItem::DISPLAY_GRID:
                Html::addCssClass($itemOptions, 'dropdown dropdown-full-width');
                break;
        }
        ?>
        <?= Html::beginTag($tag, $itemOptions) ?>
        <?= $this->render($widget->itemView, ['model' => $item, 'widget' => $widget, 'level' => $level]) ?>
        <?php if (!$widget->depth || $level < $widget->depth) : ?>
            <?= $this->render('menu', ['widget' => $widget, 'items' => $item->children, 'parent' => $item, 'level' => $level + 1]) ?>
        <?php endif ?>
        <?= Html::endTag($tag) ?>
    <?php endif ?>
<?php endforeach ?>
<?= $this->render('menu_items_end', ['parent' => $parent, 'widget' => $widget, 'level' => $level]) ?>
<?php endif ?>

