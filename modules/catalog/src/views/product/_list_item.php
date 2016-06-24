<?php

/* @var $this yii\web\View */
/* @var $model \im\catalog\models\Product */
/* @var $image \im\catalog\models\ProductCategoryFile */

?>
<div class="thumbnail">
    <a href="<?= $model->getUrl() ?>" title="<?= $model->title ?>">
        <?php if ($image = $model->getCover()) : ?>
            <img src="<?= $image ?>" class="img-responsive" alt="<?= $image->title ?: $model->title ?>" style="width: 100%">
        <?php endif ?>
        <?= $model->title ?>
    </a>
</div>

