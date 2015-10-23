<?php

use im\base\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\ProductCategory */
/* @var $form yii\widgets\ActiveForm */

?>

<?= Html::hiddenInput('uploadedImage') ?>
<?= ListView::widget([
    'dataProvider' => new ActiveDataProvider([
        'query' => $model->imageRelation()
    ]),
    'itemView' => '@im/catalog/backend/views/product-category-file/_form',
    'mode' => ListView::MODE_GRID,
    'addLabel' => false,
    'viewParams' => ['form' => $form, 'fieldConfig' => ['namePrefix' => 'uploadedImage']]
]); ?>