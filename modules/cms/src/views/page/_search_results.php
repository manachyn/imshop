<?php

/* @var $this yii\web\View */
/* @var $dataProvider im\search\components\search\SearchDataProvider */

echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '@im/cms/views/page/_search_results_item',
    'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}",
    'itemOptions' => ['class' => 'col-xs-6 col-sm-4 col-md-4 col-lg-3']
]);
