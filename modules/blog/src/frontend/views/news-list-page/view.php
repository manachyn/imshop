<?php

/* @var $this yii\web\View */
/* @var $model im\search\models\SearchPage */
/* @var $dataProvider im\search\components\search\SearchDataProvider */
/* @var $searchableType im\search\components\searchable\SearchableInterface */

$this->title = $model->title;
$this->params['breadcrumbs'] = [$this->title];
?>

<?php if ((!$dataProvider || $dataProvider->pagination->page <= 1) && $model->content) : ?>
    <div class="typography">
        <?= $model->content ?>
    </div>
<?php endif ?>

<?php if ($dataProvider) : ?>
<div class="search-results">
<?= $searchableType->getSearchResultsView()
    ? $this->render($searchableType->getSearchResultsView(), [
        'context' => $model,
        'dataProvider' => $dataProvider,
        'searchableType' => $searchableType
    ]) : ''
?>
</div>
<?php endif ?>