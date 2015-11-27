<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $item \im\cms\models\MenuItem */
/* @var $parent \im\cms\models\MenuItem */
/* @var $level int */

$itemOptions = $item->getHtmlAttributes();
$tag = ArrayHelper::remove($itemOptions, 'tag', 'li');
$icon = ($item->isActive() && $activeIcon = $item->activeIcon) ? $activeIcon : (($icon = $item->icon) ? $icon : false);
if ($icon) {
    $item->label = '<img src="' . $icon . '" alt="' . ($icon->title ?: $item->title) . '"> ' . $item->label;
}

echo Html::beginTag($tag, $itemOptions);
echo Html::a($item->label, $item->url, $item->getLinkHtmlAttributes());
if ((!$widget->depth || $level < $widget->depth) && $items = $item->children) :
    echo $this->render('menu_items_start', ['widget' => $widget, 'parent' => $item,'level' => $level + 1]);
    echo $this->render('menu_items', ['widget' => $widget, 'items' => $items, 'parent' => $item, 'level' => $level + 1]);
    echo $this->render('menu_items_end', ['widget' => $widget, 'parent' => $item, 'level' => $level + 1]);
endif;
echo Html::endTag($tag);
