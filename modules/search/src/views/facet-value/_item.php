<?php

/* @var $this yii\web\View */
/* @var $model \im\search\components\query\facet\FacetValueInterface */
/* @var $searchQuery im\search\components\query\SearchQueryInterface */
/* @var $level int */

?>

<a href="<?= $model->createUrl(null, $searchQuery) ?>"><?= $model->getLabel() ?></a> (<?= $model->getResultsCount() ?>)

