<?php

use im\cms\models\MenuItem;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $parent \im\cms\models\MenuItem */
/* @var $level int */

$options = $level === 1 ? $widget->options : [];
$html = Html::beginTag('ul', $options);

if ($parent) {
    switch ($parent->items_display) {
        case MenuItem::DISPLAY_DROPDOWN:
            $html = "<ul class=\"dropdown-menu\">\n";
            break;
        case MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN:
            $html = "<ul class=\"dropdown-menu\">\n<li>\n<div class=\"navbar-mega-content\">\n<ul>\n";
            break;
        case MenuItem::DISPLAY_GRID:
            $html = "<ul class=\"dropdown-menu\">\n<li>\n<div class=\"navbar-mega-content\">\n<div class=\"row\">\n";
            break;
    }
}

echo $html;


