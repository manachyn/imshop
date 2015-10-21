<?php

use im\search\components\query\facet\TreeFacetInterface;
use im\tree\widgets\Tree;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $facets im\search\components\query\facet\FacetInterface[] */
/* @var $selectedFacets im\search\components\query\facet\FacetInterface[] */
/* @var $searchComponent im\search\components\search\SearchComponent */
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
                <?php foreach ($values as $value) :
//                    $params['query'] = '';
//                    if ($searchQuery = $value->getSearchQuery()) {
//                        $params['query'] = $searchComponent->queryConverter->toString($searchQuery);
//                    }
                    ?>
                    <li class="facet-value"><a href="<?= $value->createUrl(null, $searchQuery) ?>"><?= $value->getLabel() ?></a></li>
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
        if ($facet instanceof TreeFacetInterface) {
            $values = $facet->getValuesTree();
        } else {
            $values = $facet->getValues();
        }
        ?>
        <?php if ($values) : ?>
        <li class="facet">
            <span class="facet-label"><?= $facet->getLabel() ?></span>
            <?php if ($facet instanceof TreeFacetInterface) : ?>
                <?= Tree::widget([
                    'items' => $values,
                    'itemView' => '@im/search/views/facet-value/_item',
                    'itemViewParams' => ['searchQuery' => $searchQuery]
                ]) ?>
            <?php else : ?>
                <ul class="facet-values">
                <?php foreach ($values as $value) :
    //                $params = [];
    //                if ($searchQuery = $value->getSearchQuery()) {
    //                    $params['query'] = $searchComponent->queryConverter->toString($searchQuery);
    //                }
                    ?>
                    <li class="facet-value"><a href="<?= $value->createUrl(null, $searchQuery) ?>"><?= $value->getLabel() ?></a> (<?= $value->getResultsCount() ?>)</li>
                <?php endforeach ?>
                </ul>
            <?php endif ?>
            <?php endif ?>
        </li>
    <?php endforeach ?>
    </ul>
</nav>
<?php endif ?>