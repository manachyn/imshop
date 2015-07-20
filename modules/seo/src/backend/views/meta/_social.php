<?php

use im\base\widgets\Block;
use yii\bootstrap\Tabs;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model im\seo\models\Meta */
/* @var $form yii\widgets\ActiveForm */

$socialMeta = $model->socialMeta ? $model->socialMeta : $model->getEnabledSocialMeta();
foreach ($socialMeta as $meta) {
    $content = Block::begin(); ?>
    <?= $this->render('@im/seo/backend/views/' . Inflector::camel2id($meta->formName()) . '/_main', [
        'model' => $meta,
        'form' => $form
    ]) ?>
    <?php Block::end();
    $tabs[] = [
        'label' => $meta->getSocialName(),
        'content' => $content
    ];
}

if ($tabs) { ?>
    <div class="nav-tabs-custom">
        <?= Tabs::widget(['items' => $tabs]); ?>
    </div>
<?php }