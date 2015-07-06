<?php

use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $page im\cms\models\Page */
/* @var $form yii\widgets\ActiveForm */

$layouts = $page->getAvailableLayoutsList();
$layout = $page->getLayout(true, true);
?>

<?php if ($layouts) { ?>
<div class="row">
    <div class="col-sm-6">

        <?= $form->field($page, 'layout_id')->dropDownList(
            $layouts,
            [
                'prompt' => $page->getDefaultLayout()->getName(),
                'onchange' => new \yii\web\JsExpression("$.pjax({container:'#layoutEditor', push: false, url: '"
                        . Url::toRoute([
                            'layout/layout',
                            'ownerId' => $page->id,
                            'ownerType' => $page->getOwnerType(),
                            'id' => ''
                        ]) . "' + this.value}); return false;")
            ]
        ) ?>

    </div>
</div>
<?php } ?>

<?php
Pjax::begin(['id' => 'layoutEditor', 'enablePushState' => false]);
echo $this->render('/layout/_layout_editor', [
    'layout' => $layout
]);
Pjax::end();
?>


