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
$itemsOptions = [];
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
            $itemsOptions['class'] = $parent->items_css_classes;
            break;
    }
}
?>

<?php if ($items) : ?>
<?= $this->render('menu_items_start', ['parent' => $parent, 'widget' => $widget, 'level' => $level]) ?>
<?php foreach ($items as $item) : ?>
    <?php if ($item->isVisible()) :
        $itemOptions = array_merge_recursive($itemsOptions, $item->getHtmlAttributes());
        if ($isActive = $widget->isItemActive($item)) {
            Html::addCssClass($itemOptions, 'active');
        }
        $linkOptions = [];
        $children = $item->children;
        switch ($item->items_display) {
            case MenuItem::DISPLAY_DROPDOWN:
                if ($children) {
                    Html::addCssClass($itemOptions, 'dropdown');
                    //$linkOptions['data-toggle'] = 'dropdown';
                    $linkOptions['aria-haspopup'] = "true";
                    $linkOptions['aria-expanded'] = "false";
                    Html::addCssClass($linkOptions, 'dropdown-toggle');
                    $item->label .= ' <span class="caret"></span>';
                }
                break;
            case MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN:
                if ($children) {
                    Html::addCssClass($itemOptions, 'dropdown dropdown-full-width');
                    //$linkOptions['data-toggle'] = 'dropdown';
                    $linkOptions['aria-haspopup'] = "true";
                    $linkOptions['aria-expanded'] = "false";
                    Html::addCssClass($linkOptions, 'dropdown-toggle');
                    $item->label .= ' <span class="caret"></span>';
                }
                break;
            case MenuItem::DISPLAY_GRID:
                if ($children) {
                    Html::addCssClass($itemOptions, 'dropdown dropdown-full-width');
                    //$linkOptions['data-toggle'] = 'dropdown';
                    $linkOptions['aria-haspopup'] = "true";
                    $linkOptions['aria-expanded'] = "false";
                    Html::addCssClass($linkOptions, 'dropdown-toggle');
                    $item->label .= ' <span class="caret"></span>';
                }
                break;
        }
        ?>
        <?= Html::beginTag($tag, $itemOptions) ?>
        <?= $this->render($widget->itemView, ['model' => $item, 'widget' => $widget, 'level' => $level, 'linkOptions' => $linkOptions, 'isActive' => $isActive], $widget) ?>
        <?php if (!$widget->depth || $level < $widget->depth) : ?>
            <?= $this->render('menu', ['widget' => $widget, 'items' => $item->children, 'parent' => $item, 'level' => $level + 1]) ?>
        <?php endif ?>
        <?= Html::endTag($tag) ?>
    <?php endif ?>
<?php endforeach ?>
<?= $this->render('menu_items_end', ['parent' => $parent, 'widget' => $widget, 'level' => $level]) ?>
<?php endif ?>

