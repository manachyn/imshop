<?php

/* @var $this yii\web\View */
/* @var $model im\blog\models\NewsListPage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'] = [$this->title];
?>

<?php if ((!$dataProvider || $dataProvider->pagination->page <= 1) && $model->content) : ?>
    <div class="typography">
        <?= $model->content ?>
    </div>
<?php endif ?>

<?php if ($dataProvider) : ?>
<?= $this->render('../news/_list', [
    'context' => $model,
    'dataProvider' => $dataProvider
]) ?>
<?php endif ?>