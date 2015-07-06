<?php
/**
 * Theme main layout.
 */
use app\modules\base\widgets\RemotePjaxModal;

/* @var $this \yii\web\View */
/* @var $content string */

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('//layouts/head') ?>
</head>
<body class="skin-blue sidebar-mini">
<?php $this->beginBody() ?>
    <div class="wrapper">
        <?= $this->render('//layouts/header') ?>
        <?= $this->render('//layouts/sidebar') ?>
        <?= $this->render('//layouts/content', ['content' => $content]) ?>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

