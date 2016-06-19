<?php

/**
 * Theme home layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use im\cms\widgets\WidgetArea;
use im\pkbnt\components\assets\HomeAsset;
use im\pkbnt\widgets\FlashMessages;

HomeAsset::register($this);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('//layouts/head') . "\n" ?>
</head>
<body>
<?php $this->beginBody(); ?>
<?= FlashMessages::widget(); ?>
<?= $this->render('//layouts/header') ?>
<?= $this->render('//layouts/top-menu') ?>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-2">
            <?= $this->render('//layouts/content', ['content' => $content]) ?>
            <?= WidgetArea::widget([
                'code' => 'bottom',
                'layout' => 'main',
                'context' => $this->context
            ]) ?>
        </div>
        <div class="col-md-2 col-md-10">
            <?= WidgetArea::widget([
                'code' => 'sidebar',
                'layout' => 'main',
                'context' => $this->context
            ]) ?>
        </div>
    </div>
</div>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>