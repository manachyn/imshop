<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $items \im\cms\models\MenuItem[] */
/* @var $level int */

?>

<?php if ($items) : ?>
    <?= Html::beginTag('ul', $widget->options) ?>

    <?= Html::endTag('ul') ?>
<?php endif ?>