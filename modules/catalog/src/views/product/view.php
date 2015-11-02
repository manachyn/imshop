<?php

/**
 * Product view.
 *
 * @var \yii\web\View $this View
 * @var \im\catalog\models\Product $model Model
 */

$this->title = $model->title;
//$model->pageMeta->applyTo($this);
$this->params['breadcrumbs'] = [$this->title];
//$this->params['model'] = $model;
?>
<?= $model->description ?>