<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $items \im\cms\models\MenuItem[] */
/* @var $level int */

$options = $level === 1 ? $widget->options : ['class' => 'dropdown-menu'];

?>

<?php if ($items) : ?>
    <?= Html::beginTag('ul', $options) ?>
        <?php foreach ($items as $item) : ?>
            <?php if ($item->isVisible()) :
                $children = $item->children;
                $itemOptions = $item->getHtmlAttributes();
                $linkOptions = $item->getLinkHtmlAttributes();
                if ($children) {
                    //$linkOptions['data-toggle'] = 'dropdown';
                    $linkOptions['aria-haspopup'] = "true";
                    $linkOptions['aria-expanded'] = "false";
                    Html::addCssClass($itemOptions, ['widget' => 'dropdown dropdown-full-width']);
                    Html::addCssClass($linkOptions, ['widget' => 'dropdown-toggle']);
                    if ($widget->dropDownCaret !== '') {
                        $item->label .= ' ' . $widget->dropDownCaret;
                    }
                }
            ?>
            <?= Html::beginTag('li', $itemOptions) ?>
                <?= $this->render($widget->itemView, ['model' => $item, 'level' => $level, 'linkOptions' => $linkOptions]) ?>
                <?php if (!$widget->depth || $level < $widget->depth) : ?>
                    <?= $this->render('menu', ['widget' => $widget, 'items' => $children, 'level' => $level + 1]) ?>
                <?php endif ?>
            <?= Html::endTag('li') ?>
            <?php endif ?>
        <?php endforeach ?>
    <?= Html::endTag('ul') ?>
<?php endif ?>