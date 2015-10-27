<?php

use im\cms\models\MenuItem;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $model \im\cms\models\MenuItem */
/* @var $level int */

$linkOptions = $model->getLinkHtmlAttributes();

//if ($model->items_display === MenuItem::DISPLAY_DROPDOWN || $model->items_display === MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN) {
//    //$linkOptions['data-toggle'] = 'dropdown';
//    $linkOptions['aria-haspopup'] = "true";
//    $linkOptions['aria-expanded'] = "false";
//    Html::addCssClass($linkOptions, 'dropdown-toggle');
//}

?>

<?= Html::a($model->label, $model->url, $linkOptions) ?>
