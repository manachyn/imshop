<?php

/**
 * Theme main layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use im\imshop\widgets\FlashMessages;

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
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>