<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $items \im\cms\models\MenuItem[] */

echo Html::tag('ul', $this->render('menu_items', ['widget' => $widget, 'items' => $items, 'level' => 1]), $widget->options);

