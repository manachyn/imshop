<?php

use im\cms\models\MenuItem;
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
    if ($level === 1) {
        $item->label = '<img src="' . $icon . '" alt="' . ($icon->title ?: $item->title) . '" height="18"> ' . $item->label;
    } else {
        $item->label = '<img src="' . $icon . '" alt="' . ($icon->title ?: $item->title) . '" class="img-responsive"> ' . $item->label;
    }
}

echo Html::beginTag($tag, $itemOptions);
echo Html::a($item->label, $item->url, $item->getLinkHtmlAttributes());
if ((!$widget->depth || $level < $widget->depth) && $items = $item->children) :
    if ($level == 1 && ($item->items_display == MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN || $item->items_display == MenuItem::DISPLAY_GRID) && $item->video) :
        if ($item->items_display == MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN) :
            echo "<ul class=\"dropdown-menu\">\n<li>\n<div class=\"navbar-mega-content\">\n<div class=\"container-fluid\">\n<div class=\"row\">\n<div class=\"col-sm-9\">\n<ul>\n";
        else :
            echo "<ul class=\"dropdown-menu\">\n<li>\n<div class=\"navbar-mega-content\">\n<div class=\"container-fluid\">\n<div class=\"row\">\n<div class=\"col-sm-9\">\n<div class=\"row\">\n";
        endif;
    else :
        echo $this->render('@im/cms/widgets/views/menu/menu_items_start', ['widget' => $widget, 'parent' => $item,'level' => $level + 1]);
    endif;

    echo $this->render('@im/cms/widgets/views/menu/menu_items', ['widget' => $widget, 'items' => $items, 'parent' => $item, 'level' => $level + 1]);

    if ($level == 1 && ($item->items_display == MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN || $item->items_display == MenuItem::DISPLAY_GRID) && $item->video) :
        $video = "<div class=\"col-sm-3\">\n<video width=\"100%\" controls>\n<source src=\"{$item->video->getUrl()}\" type=\"{$item->video->mime_type}\">\n</video>\n</div>";
        if ($item->items_display == MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN) :
            echo "</ul>\n</div>\n$video</div>\n</div>\n</div>\n</li>\n</ul>\n";
        else :
            echo "</div>\n</div>\n$video</div>\n</div>\n</div>\n</li>\n</ul>\n";
        endif;
    else :
        echo $this->render('@im/cms/widgets/views/menu/menu_items_end', ['widget' => $widget, 'parent' => $item, 'level' => $level + 1]);
    endif;
endif;
echo Html::endTag($tag);
