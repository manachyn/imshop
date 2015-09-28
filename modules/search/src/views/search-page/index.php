<?php

/* @var $this yii\web\View */
/* @var $dataProvider im\search\components\SearchDataProvider */

$this->title = 'Search Results';
$this->params['breadcrumbs'] = [
    $this->title
];
?>

<?= \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '@im/catalog/views/product/_item.php'
]) ?>