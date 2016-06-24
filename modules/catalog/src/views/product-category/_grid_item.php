<?php

/* @var $this yii\web\View */
/* @var $model \im\catalog\models\ProductCategory */
/* @var $image \im\catalog\models\ProductCategoryFile */

?>

<a href="<?= $model->getUrl() ?>" title="<?= $model->name ?>">
    <?php if ($image = $model->image) : ?>
        <img src="<?= $image ?>" class="img-responsive" alt="<?= $image->title ?: $model->name ?>" style="width: 100%">
    <?php endif ?>
    <?= $model->name ?>
</a>

