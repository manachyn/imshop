<?php

/**
 * @var yii\web\View $this View
 * @var im\catalog\models\ProductCategory $model Model
 */

?>

<div class="row list-view product-categories-list">
<?php foreach ($model->children(1)->all() as $category) : ?>
    <div class="list-view-item product-categories-list-item col-lg-12">
        <?= $this->render('_list_item', ['model' => $category]) ?>
    </div>
<?php endforeach ?>
</div>