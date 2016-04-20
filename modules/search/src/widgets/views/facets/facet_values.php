<?php

use im\search\components\query\facet\TreeFacetValueInterface;

/* @var $this yii\web\View */
/* @var $values im\search\components\query\facet\FacetValueInterface[] */
/* @var $facet im\search\components\query\facet\FacetInterface */
/* @var $searchQuery im\search\components\query\SearchQueryInterface */
/* @var $level int */

$level = isset($level) ? $level : 1;
?>

<?php if ($values) : ?>
<ul<?= $level == 1 ? ' class="facet-values"' : '' ?>>
<?php foreach ($values as $value) :
    if ($value->getResultsCount() || $facet->isDisplayValuesWithoutResults()) : ?>
    <li class="facet-value">
    <?= $this->render('facet_value', ['value' => $value, 'facet' => $facet, 'searchQuery' => $searchQuery, 'level' => $level]) ?>
    <?php if ($value instanceof TreeFacetValueInterface) : ?>
        <?= $this->render('facet_values', ['values' => $value->getChildren(), 'facet' => $facet, 'searchQuery' => $searchQuery, 'level' => $level + 1]) ?>
    <?php endif ?>
    </li>
    <?php endif ?>
<?php endforeach ?>
</ul>
<?php endif ?>