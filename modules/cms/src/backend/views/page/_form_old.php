<?php

use im\cms\Module;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $page im\cms\models\Page */
/* @var $pageMeta im\cms\models\PageMeta */
/* @var $openGraph im\cms\models\OpenGraph */
/* @var $form yii\widgets\ActiveForm */
/* @var $box app\themes\admin\widgets\Box */
?>

<?php $form = ActiveForm::begin(); ?>
<?php $box->beginBody(); ?>

<div class="nav-tabs-custom">
<?php
    $tabs = [
        [
            'label' => Module::t('page', 'Main'),
            'content' => $this->render('_main', ['page' => $page, 'form' => $form]),
            'active' => true
        ],
        [
            'label' => Module::t('page', 'Meta information'),
            'content' => $this->render('_meta', ['pageMeta' => $pageMeta, 'openGraph' => $openGraph, 'form' => $form])
        ]
    ];
    if ($page->hasProperty('useLayout'))
        $tabs[] = [
            'label' => 'Layout',
            'content' => $this->render('_layout', ['page' => $page, 'form' => $form])
        ];
    ?>
    <?= Tabs::widget([
        'items' => $tabs
    ]);
?>
</div>
<?php $box->endBody(); ?>
<?php $box->beginFooter(); ?>
<?= Html::submitButton(
    $page->isNewRecord ? Module::t('page', 'Create') : Module::t('page', 'Update'),
    ['class' => $page->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']
) ?>
<?php $box->endFooter(); ?>
<?php ActiveForm::end(); ?>
