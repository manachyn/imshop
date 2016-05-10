<?php

use app\modules\formBuilder\widgets\FieldSet;
use app\modules\users\Module;
use app\themes\admin\widgets\Box;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */
/* @var $component im\config\components\ConfigurableInterface */

$this->title = Module::t('config', 'Settings');
$this->params['subtitle'] = $component->getConfigTitle() . ' ' . Module::t('config', 'settings');
$this->params['breadcrumbs'] = [['label' => $this->title, 'url' => ['index']], $this->params['subtitle']];
$boxButtons = ['{cancel}'];
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;
?>
<div class="row">
    <div class="col-sm-12">
        <?php $box = Box::begin(
            [
                'title' => $this->params['subtitle'],
                'renderBody' => false,
                'options' => [
                    'class' => 'box-success'
                ],
                'buttonsTemplate' => $boxButtons
            ]
        );

        $form = ActiveForm::begin();

        $box->beginBody();

        echo FieldSet::widget(['form' => $form, 'model' => $model, 'fields' => [
            'option1' => ['fieldType' => 'input', 'inputOptions' => ['type' => 'text']],
//            'option2' => ['fieldType' => 'input', 'inputOptions' => ['type' => 'hidden']],
//            'option3' => ['fieldType' => 'input', 'inputOptions' => ['type' => 'password']],
//            'option4' => ['fieldType' => 'input', 'inputOptions' => ['type' => 'file']],
            'option5' => ['fieldType' => 'textarea']
        ]]);

        $box->endBody();

        $box->beginFooter();
        echo Html::submitButton(Module::t('config', 'Save'), ['class' => 'btn btn-success btn-large']);
        $box->endFooter();

        ActiveForm::end();

        Box::end(); ?>
    </div>
</div>
