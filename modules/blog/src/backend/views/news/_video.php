<?php

use im\base\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model im\blog\models\News */
/* @var $form yii\widgets\ActiveForm */

?>

<?= Html::hiddenInput('uploadedVideo') ?>
<?= ListView::widget([
    'dataProvider' => new ActiveDataProvider([
        'query' => $model->videoRelation()
    ]),
    'itemView' => '@im/blog/backend/views/article-file/_form',
    'addLabel' => false,
    'viewParams' => ['form' => $form, 'fieldConfig' => ['namePrefix' => 'uploadedVideo']]
]); ?>
