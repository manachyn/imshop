<?php

/**
 * Page view.
 * @var \yii\web\View $this View
 * @var \im\cms\models\Page $model Model
 */

$this->title = $model->title;
//$model->pageMeta->applyTo($this);
foreach ($model->getParents() as $parent) {
    $this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => $parent->getUrl()];
}
if ($model->slug != 'index') {
    $this->params['breadcrumbs'][] = $model->getUrl();
}
//$this->params['model'] = $model;
?>
<div class="typography">
    <?= Yii::$app->get('shortcodes')->parse($model->content) ?>
</div>