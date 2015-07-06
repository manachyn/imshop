<?php

use app\modules\formBuilder\widgets\FieldSet;

/* @var $this yii\web\View */
/* @var $page im\cms\models\Page */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row">
    <div class="col-sm-6">

        <?= FieldSet::widget([
            'form' => $form,
            'model' => $page,
            'fields' => $page->getFormFields(),
            //'displayFields' => $attributes
        ]); ?>

    </div>
</div>
