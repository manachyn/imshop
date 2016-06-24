<?php

/* @var $this yii\web\View */
/* @var $model \im\catalog\models\ProductCategory */
/* @var $image \im\catalog\models\ProductCategoryFile */

?>

<div class="media">
    <?php if ($image = $model->image) : ?>
    <div class="media-left">
        <a href="<?= $model->getUrl() ?>" title="<?= $model->name ?>">
            <img src="<?= $image ?>" class="media-object" alt="<?= $image->title ?: $model->name ?>">
        </a>
    </div>
    <?php endif ?>
    <div class="media-body">
        <h4 class="media-heading">
            <a href="<?= $model->getUrl() ?>" title="<?= $model->name ?>"><?= $model->name ?></a>
        </h4>
        <?= $model->description ?>
    </div>
</div>
