<?php

/**
 * Theme home layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use im\imshop\widgets\FlashMessages;

//$this->registerJs('
//require(["im/imshop/a"], function(a) {
//    console.log(a);
//});
//', \yii\web\View::POS_END, null, ['depends' => ['im\imshop\BAsset']]);

\app\assets\AllAsset::register($this);

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