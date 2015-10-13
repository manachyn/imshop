<?php

/* @var $this yii\web\View */
/* @var $model im\search\models\SearchPage */
/* @var $dataProvider im\search\components\search\SearchDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'] = [
    $this->title
];
?>

<?php if ($dataProvider->pagination->page <= 1) :
    echo $model->content;
endif ?>


<?= \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '@im/catalog/views/product/_item.php',
    'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}",
    'itemOptions' => ['class' => 'col-xs-6 col-sm-4 col-md-4 col-lg-3']
]) ?>