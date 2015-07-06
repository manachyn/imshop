<?php

use app\modules\base\widgets\Block;
use im\cms\Module;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $page im\cms\models\Page */
/* @var $pageMeta im\cms\models\PageMeta */
/* @var $openGraph im\cms\models\OpenGraph */
/* @var $form yii\widgets\ActiveForm */

$tabs = [];

?>

<?php $form = ActiveForm::begin(['id' => 'batch-update-form', 'options' => ['data-pjax' => 1]]); ?>

<?php $content = Block::begin(); ?>
<div class="row">
    <div class="col-sm-6">
        <?= $this->render('_main', [
            'model' => $page,
            'form' => $form
        ]) ?>
    </div>
</div>
<?php Block::end();
$tabs[] = [
    'label' => Module::t('page', 'Main'),
    'content' => $content,
    'active' => true
];

$content = Block::begin(); ?>
<div class="row">
    <div class="col-sm-6">
        <?= $this->render('/page-meta/_main', [
            'model' => $pageMeta,
            'form' => $form,
            'attributes' => $attributes['pageMeta']
        ]) ?>
    </div>
    <div class="col-sm-6">
        <h4><?= Module::t('page', 'Social Meta Tags') ?></h4>
        <?= $this->render('/open-graph/_main', [
            'model' => $openGraph,
            'form' => $form,
            'attributes' => $attributes['openGraph']
        ]) ?>
    </div>
</div>
<?php Block::end();
$tabs[] = [
    'label' => Module::t('page', 'Meta information'),
    'content' => $content
];

if ($page->hasProperty('useLayout')) {
    $content = Block::begin(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $this->render('_layout', [
                'model' => $page,
                'form' => $form
            ]) ?>
            <?php
            $layout = $page->getLayout(true, true);
            Pjax::begin(['id' => 'layoutEditor', 'enablePushState' => false]);
            echo $this->render('/layout/_layout_editor', ['layout' => $layout]);
            Pjax::end();
            ?>
        </div>
    </div>
    <?php Block::end();
    $tabs[] = [
        'label' => Module::t('page', 'Layout'),
        'content' => $content
    ];
} ?>

<?php $box->beginBody(); ?>
    <?php if ($tabs) { ?>
        <div class="nav-tabs-custom">
            <?= Tabs::widget(['items' => $tabs]); ?>
        </div>
    <?php } ?>
<?php $box->endBody(); ?>

<?php $box->beginFooter(); ?>
<?= Html::submitButton(
    $page->isNewRecord ? Module::t('page', 'Create') : Module::t('page', 'Update'),
    ['class' => $page->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']
) ?>
<?php $box->endFooter(); ?>

<?php ActiveForm::end(); ?>


