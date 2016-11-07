<?php

/* @var $this yii\web\View */
/* @var $model \im\catalog\models\Product */
/* @var $image \im\catalog\models\ProductCategoryFile */

?>

<div class="media">
    <?php if ($image = $model->getCover()) : ?>
        <div class="media-left">
            <a href="<?= $model->getUrl() ?>" title="<?= $model->title ?>">
                <img src="<?= $image ?>" class="media-object" alt="<?= $image->title ?: $model->title ?>">
            </a>
        </div>
    <?php endif ?>
    <div class="media-body">
        <h4 class="media-heading">
            <a href="<?= $model->getUrl() ?>" title="<?= $model->title ?>"><?= $model->title ?></a>
        </h4>
        <?= $model->short_description ?>
    </div>
    <div class="media-right">
        <div class="product-price">
            <strong><?= $model->price ?> грн.</strong>
        </div>
        <div class="product-availability">
            Наличие: <?= $model->availability ? 'есть' : 'нету в наличии' ?>
        </div>
        <div class="product-sku">
            Код товара: <?= $model->sku ?>
        </div>
    </div>
</div>

