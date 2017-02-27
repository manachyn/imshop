<?php

use im\cms\widgets\Menu;

/* @var $this yii\web\View */
/* @var $widget im\cms\widgets\MenuWidget */

?>

<div class="widget menu-widget">
    <?php if ($widget->title) : ?>
        <h2 class="widget-title"><?= $widget->title ?></h2>
    <?php endif ?>
    <div class="widget-content">
        <?= Menu::widget(['menuId' => $widget->model_id]); ?>
    </div>
</div>