<?php

use im\cms\models\MenuItem;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $model \im\cms\models\MenuItem */
/* @var $level int */
/* @var $linkOptions array */

$linkOptions = isset($linkOptions) ? array_merge_recursive($linkOptions, $model->getLinkHtmlAttributes()) : $model->getLinkHtmlAttributes();
?>

<?= Html::a($model->label, $model->url, $linkOptions) ?>