<?php

use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $context im\blog\models\ArticlesListPage */
/* @var $dataProvider yii\data\ActiveDataProvider */

echo ListView::widget([
    'id' => 'articles-list',
    'dataProvider' => $dataProvider,
    'itemView' => '_list_item',
    //'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}",
    'itemOptions' => ['class' => 'articles-list-item'],
    //'pager' => ['route' => '/cms/page/view']
]);
