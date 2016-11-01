<?php

/**
 * Product category view.
 * @var yii\web\View $this View
 * @var im\catalog\models\ProductCategory $model Model
 * @var im\search\components\search\SearchDataProvider $productsDataProvider
 */

use yii\widgets\ListView;

$this->title = $model->name;
foreach ($model->getParents() as $parent) {
    $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => $parent->getUrl()];
}
$this->params['breadcrumbs'][] = $this->title;
$this->params['model'] = $model;
?>

<?= ListView::widget([
    'dataProvider' => $productsDataProvider,
    'itemView' => '@im/catalog/views/product/_list_item.php',
    'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}",
    'itemOptions' => ['class' => 'list-view-item col-xs-6 col-sm-4 col-md-4 col-lg-3'],
    'emptyText' => ''
]) ?>

<?= $this->render('_list', ['model' => $model]) ?>

<?= $model->content ?>