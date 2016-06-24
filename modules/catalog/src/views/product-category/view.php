<?php

/**
 * Product category view.
 * @var \yii\web\View $this View
 * @var \im\catalog\models\ProductCategory $model Model
 */

use yii\widgets\ListView;

$this->title = $model->name;
$this->params['breadcrumbs'] = [
    $this->title
];
$this->params['model'] = $model;
?>

<?= ListView::widget([
    'dataProvider' => $productsDataProvider,
    'itemView' => '@im/catalog/views/product/_list_item.php',
    'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}",
    'itemOptions' => ['class' => 'col-xs-6 col-sm-4 col-md-4 col-lg-3'],
    'emptyText' => ''
]) ?>

<div class="row">
<?php foreach ($model->children(1)->all() as $category) : ?>
    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
        <?= $this->render('_list_item', ['model' => $category]) ?>
    </div>
<?php endforeach ?>
</div>

<?= $model->content ?>