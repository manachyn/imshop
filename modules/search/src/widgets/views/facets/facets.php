<?php

use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\facet\TreeFacetInterface;
use im\tree\widgets\Tree;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $facets im\search\components\query\facet\FacetInterface[] */
/* @var $selectedFacets im\search\components\query\facet\FacetInterface[] */
/* @var $searchQuery im\search\components\query\SearchQueryInterface */

?>

<?php if ($selectedFacets) : ?>
<nav>
    <ul class="selected-facets">
        <?php foreach ($selectedFacets as $facet) : ?>
            <?php if ($values = $facet->getValues()) : ?>
            <li class="selected-facet">
                <span class="selected-facet-label"><?= $facet->getLabel() ?></span>
                <ul class="selected-facet-values">
                <?php foreach ($values as $value) : ?>
                    <?= $this->render('facet_value_selected', ['value' => $value, 'facet' => $facet, 'searchQuery' => $searchQuery]) ?>
                <?php endforeach ?>
                </ul>
            <?php endif ?>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<?php endif ?>

<?php if ($facets) : ?>
<nav>
    <ul class="facets">
    <?php foreach ($facets as $facet) :
        $values = $facet instanceof TreeFacetInterface ? $facet->getValuesTree($this->context) : $facet->getValues();
        $hasResults = $facet instanceof TreeFacetInterface ? true : $facet->isHasResults();
        if ($values && $hasResults) : ?>
        <li class="facet">
            <span class="facet-label"><?= $facet->getLabel() ?></span>
            <?= $this->render('facet_values', ['values' => $values, 'facet' => $facet, 'searchQuery' => $searchQuery]) ?>
        <?php endif ?>
        </li>
    <?php endforeach ?>
    </ul>
</nav>
<?php endif ?>