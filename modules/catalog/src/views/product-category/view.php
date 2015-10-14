<?php

/**
 * Product category view.
 * @var \yii\web\View $this View
 * @var \im\catalog\models\ProductCategory $model Model
 */

$this->title = $model->name;
//$model->pageMeta->applyTo($this);
$this->params['breadcrumbs'] = [
    $this->title
];
$this->params['model'] = $model;
?>
<?= $model->description ?>

<div class="row">
<?php foreach ($model->children(1)->all() as $category) : ?>
    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
        <?= $this->render('_item', ['model' => $category]) ?>
    </div>
<?php endforeach ?>
</div>

<?= \yii\widgets\ListView::widget([
    'dataProvider' => $productsDataProvider,
    'itemView' => '@im/catalog/views/product/_item.php',
    'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}",
    'itemOptions' => ['class' => 'col-xs-6 col-sm-4 col-md-4 col-lg-3']
]) ?>