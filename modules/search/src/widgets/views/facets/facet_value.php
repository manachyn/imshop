<?php

/* @var $this yii\web\View */
/* @var $value \im\search\components\query\facet\FacetValueInterface */
/* @var $facet \im\search\components\query\facet\FacetInterface */
/* @var $searchQuery im\search\components\query\SearchQueryInterface */
/* @var $level int */

$url = $value->createUrl(null, $searchQuery);
?>
<?php if ($facet->isMultivalue()) : ?>
    <a href="<?= $url ?>"><span class="icon-facet-value-checkbox"></span></a>
<?php endif ?>
<a href="<?= $url ?>"><?= $value->getLabel() ?></a> (<?= $value->getResultsCount() ?>)

