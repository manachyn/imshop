<?php

/* @var $this yii\web\View */
/* @var $context im\search\models\SearchPage */
/* @var $dataProvider im\search\components\search\SearchDataProvider */
/* @var $searchableType im\search\components\searchable\SearchableInterface */

echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '@im/cms/views/page/_site_search_results_item',
    'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}",
    'itemOptions' => ['class' => 'col-xs-6 col-sm-4 col-md-4 col-lg-3']
]);
