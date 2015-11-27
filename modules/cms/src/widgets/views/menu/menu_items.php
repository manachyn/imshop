<?php

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $items \im\cms\models\MenuItem[] */
/* @var $parent \im\cms\models\MenuItem */
/* @var $level int */

$parent = isset($parent) ? $parent : null;

if ($items) :
    foreach ($items as $item) :
        if ($item->isVisible()) :
            $widget->setMenuItemOptions($item, $parent);
            echo $this->render($widget->itemView, [
                'item' => $item,
                'parent' => $parent,
                'widget' => $widget,
                'level' => $level
            ], $widget);
        endif;
    endforeach;
endif;