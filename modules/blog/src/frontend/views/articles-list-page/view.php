<?php

/* @var $this yii\web\View */
/* @var $model im\blog\models\ArticlesListPage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'] = [$this->title];
?>

<?php if ($dataProvider) : ?>
<?= $this->render('../article/_list', [
    'context' => $model,
    'dataProvider' => $dataProvider
]) ?>
<?php endif ?>

<?php if ((!$dataProvider || $dataProvider->pagination->page <= 1) && $model->content) : ?>
    <div class="typography">
        <?= $model->content ?>
    </div>
<?php endif ?>
