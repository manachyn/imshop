<?php

use im\base\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\Product */
/* @var $form yii\widgets\ActiveForm */
/** @var im\catalog\models\ProductFile[] $images */

$dataProvider = new ActiveDataProvider([
    'query' => $model->imagesRelation()->orderBy('sort')
]);
?>

<?= Html::hiddenInput('uploadedImages') ?>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '@im/catalog/backend/views/product-file/_form',
    'itemClass' => 'im\catalog\models\ProductFile',
    'sortable' => true,
    'mode' => ListView::MODE_GRID,
    'addLabel' => false,
    'viewParams' => ['form' => $form, 'fieldConfig' => ['namePrefix' => 'uploadedImages']]
]); ?>