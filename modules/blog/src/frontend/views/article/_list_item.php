<?php

/* @var $this yii\web\View */
/* @var $model im\blog\models\News */
/* @var $image im\blog\models\ArticleFile */

?>

<div class="media">
    <?php if ($image = $model->image) : ?>
        <div class="media-left">
            <a href="<?= $model->getUrl() ?>" title="<?= $model->title ?>">
                <img src="<?= $image->getUrl(['w' => 100]) ?>" class="media-object" alt="<?= $image->title ?: $model->title ?>">
            </a>
        </div>
    <?php endif ?>
    <div class="media-body">
        <h4 class="media-heading">
            <a href="<?= $model->getUrl() ?>" title="<?= $model->title ?>"><?= $model->title ?></a>
        </h4>
        <?= $model->announce ?>
    </div>
</div>
