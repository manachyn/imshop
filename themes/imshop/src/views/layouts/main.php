<?php

/**
 * Theme main layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use im\cms\widgets\WidgetArea;
use im\imshop\widgets\FlashMessages;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('//layouts/head') . "\n" ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-push-2"><?= $content ?></div>
            <div class="col-md-2 col-md-pull-10">
                <?= WidgetArea::widget([
                    'code' => 'sidebar',
                    'layout' => 'main',
                    'template' => \im\cms\models\Template::findOne(5),
                    'context' => $this->context,
                    'enableCache' => false
                ]) ?>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>