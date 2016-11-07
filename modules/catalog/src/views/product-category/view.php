<?php

/**
 * Product category view.
 * @var yii\web\View $this View
 * @var im\catalog\models\ProductCategory $model Model
 * @var im\search\components\search\SearchDataProvider $productsDataProvider
 */

$this->title = $model->name;
foreach ($model->getParents() as $parent) {
    $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => $parent->getUrl()];
}
$this->params['breadcrumbs'][] = $this->title;
$this->params['model'] = $model;
?>

<?= $this->render('../product/_list', ['dataProvider' => $productsDataProvider]) ?>

<?= $this->render('_list', ['model' => $model]) ?>

<?= $model->content ?>