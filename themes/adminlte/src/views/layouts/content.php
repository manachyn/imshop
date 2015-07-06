<?php
/**
 * Header layout.
 */

use im\adminlte\widgets\FlashMessages;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

$assetsPublishUrl = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= $this->title ?>
        <?php if (isset($this->params['subtitle'])) : ?>
            <small><?= $this->params['subtitle'] ?></small>
        <?php endif; ?>
    </h1>
    <?= Breadcrumbs::widget(
        [
            'homeLink' => [
                'label' => '<i class="fa fa-dashboard"></i> ' . 'Home',
                'url' => ['/backend']
            ],
            'encodeLabels' => false,
            'tag' => 'ol',
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
        ]
    ) ?>
</section>

<!-- Main content -->
<section class="content">
    <?= FlashMessages::widget(); ?>
    <?= $content ?>
</section><!-- /.content -->
</div><!-- /.content-wrapper -->