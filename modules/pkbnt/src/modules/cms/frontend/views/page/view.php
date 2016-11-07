<?php

/**
 * Page view.
 * @var yii\web\View $this View
 * @var im\cms\models\Page $model Model
 */

foreach ($model->getParents() as $parent) {
    $this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => $parent->getUrl()];
}
if ($model->slug != 'index') {
    $this->params['breadcrumbs'][] = $model->title;
}
//$this->params['model'] = $model;
?>
<div class="typography">
    <?= Yii::$app->shortcodes->parse($model->content) ?>
    <div class="clearfix"></div>
</div>