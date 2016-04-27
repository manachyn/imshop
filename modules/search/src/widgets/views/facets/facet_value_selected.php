<?php

/* @var $this yii\web\View */
/* @var $value \im\search\components\query\facet\FacetValueInterface */
/* @var $facet \im\search\components\query\facet\FacetInterface */
/* @var $searchQuery im\search\components\query\SearchQueryInterface */

$url = $value->createUrl(null, $searchQuery);
?>
<a href="<?= $url ?>"><span class="icon-facet-value-remove"></span></a>
<a href="<?= $url ?>"><?= $value->getLabel() ?></a>

