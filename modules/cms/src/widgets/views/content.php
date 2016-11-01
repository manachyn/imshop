<?php

/* @var $this yii\web\View */
/* @var $widget im\cms\widgets\ContentWidget */

?>

<div class="widget content-widget">
    <?php if ($widget->title) : ?>
        <h2 class="widget-title"><?= $widget->title ?></h2>
    <?php endif ?>
    <div>
        <?= $widget->content ?>
    </div>
</div>