<?php

use im\cms\Module;
use app\modules\formBuilder\widgets\FieldSet;

/* @var $this yii\web\View */
/* @var $pageMeta im\cms\models\PageMeta */
/* @var $openGraph im\cms\models\OpenGraph */

?>

<div class="row">
    <div class="col-sm-6">
        <?= FieldSet::widget([
            'form' => $form,
            'model' => $pageMeta,
            'fields' => $pageMeta->getFormFields(),
            'displayFields' => $attributes
        ]); ?>
    </div>
    <div class="col-sm-6">
        <?php if (isset($attributes, $attributes['openGraph'])) { ?>
            <h4><?= Module::t('page', 'Social Meta Tags') ?></h4>
            <?= $this->render('_open_graph', ['openGraph' => $openGraph, 'form' => $form]) ?>
        <?php } ?>
    </div>
</div>
