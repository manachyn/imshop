<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \im\cms\models\MenuItem */
/* @var $level int */
/* @var $linkOptions array */

?>
<?= Html::a($model->label, $model->url, $linkOptions) ?>
