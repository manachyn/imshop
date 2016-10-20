<?php

/**
 * Theme main layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use im\viaz\components\assets\MainAsset;

MainAsset::register($this);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('//layouts/head') . "\n" ?>
</head>
<body>
<?php $this->beginBody(); ?>
1
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>