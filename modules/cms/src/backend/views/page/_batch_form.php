<?php

use im\base\widgets\Block;
use im\cms\Module;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $page im\cms\models\Page */
/* @var $pageMeta im\cms\models\PageMeta */
/* @var $form yii\widgets\ActiveForm */

$tabs = [];

?>

<?php $form = ActiveForm::begin(['id' => 'batch-update-form', 'options' => ['data-pjax' => 1]]); ?>

<?php if (isset($attributes, $attributes['model'])) {
    $content = Block::begin(); ?>

        <?= $this->render('_main', [
            'model' => $page,
            'form' => $form,
            'attributes' => $attributes['model']
        ]) ?>
        <?php if ($page->hasProperty('useLayout') && isset($attributes, $attributes['model']) && in_array('layout_id', $attributes['model'])) { ?>
            <?= $this->render('_layout', [
                'model' => $page,
                'form' => $form,
                'attributes' => $attributes['model']
            ]) ?>
        <?php } ?>

    <?php Block::end();
    $tabs[] = [
        'label' => Module::t('page', 'Main'),
        'content' => $content,
        'active' => true
    ];
}

if (isset($attributes, $attributes['pageMeta'])) {
    $content = Block::begin(); ?>

        <?= $this->render('_meta', [
            'model' => $pageMeta,
            'form' => $form,
            'attributes' => $attributes['pageMeta']
        ]) ?>

        <?php if (isset($attributes, $attributes['openGraph'])) { ?>
        <h4><?= Module::t('page', 'Social Meta Tags') ?></h4>
        <?= $this->render('_open_graph', [
            'model' => $openGraph,
            'form' => $form,
            'attributes' => $attributes['openGraph']
        ]) ?>
        <?php } ?>

    <?php Block::end();
    $tabs[] = [
        'label' => Module::t('page', 'Meta information'),
        'content' => $content
    ];
} ?>

<?php if ($tabs) { ?>
    <div class="nav-tabs-custom">
        <?= Tabs::widget(['items' => $tabs]); ?>
    </div>
<?php } ?>

<?= Html::submitButton(Module::t('page', 'Save'), ['class' => 'btn btn-success btn-large']) ?>

<?php ActiveForm::end(); ?>


