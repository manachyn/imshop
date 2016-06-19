<?php

/**
 * Theme main layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use im\cms\widgets\WidgetArea;
use im\pkbnt\widgets\FlashMessages;

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
        <div class="col-md-10">
            <?= $this->render('//layouts/content', ['content' => $content]) ?>
            <div class="clearfix"></div>
            <?= WidgetArea::widget([
                'code' => 'bottom',
                'layout' => 'main',
                'context' => $this->context
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= WidgetArea::widget([
                'code' => 'sidebar',
                'layout' => 'main',
                'context' => $this->context
            ]) ?>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= date('Y') ?> Вишневый сад. Все права защищены.</p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>