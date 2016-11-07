<?php

/**
 * Product view.
 *
 * @var yii\web\View $this View
 * @var im\catalog\models\Product $model Model
 */

$this->title = $model->title;
$this->params['breadcrumbs'] = [$this->title];

?>
<?= $model->description ?>