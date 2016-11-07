<?php

/**
 * @var yii\web\View $this View
 * @var im\search\components\search\SearchDataProvider $dataProvider
 */

use yii\widgets\ListView;

?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '@im/catalog/views/product/_list_item.php',
    'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}",
    'itemOptions' => ['class' => 'list-view-item col-xs-6 col-sm-4 col-md-4 col-lg-3'],
    'emptyText' => ''
]) ?>