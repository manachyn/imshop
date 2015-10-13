<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $facets im\search\components\query\facet\FacetInterface[] */
/* @var $selectedFacets im\search\components\query\facet\FacetInterface[] */
/* @var $searchComponent im\search\components\search\SearchComponent */
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
                    $params['query'] = '';
                    if ($searchQuery = $value->getSearchQuery()) {
                        $params['query'] = $searchComponent->queryConverter->toString($searchQuery);
                    }
                    ?>
                    <li class="facet-value"><a href="<?= Url::current($params) ?>"><?= $value->getLabel() ?></a></li>
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
    <?php foreach ($facets as $facet) : ?>
        <?php if ($values = $facet->getValues()) : ?>
        <li class="facet">
            <span class="facet-label"><?= $facet->getLabel() ?></span>
            <ul class="facet-values">
            <?php foreach ($values as $value) :
                $params = [];
                if ($searchQuery = $value->getSearchQuery()) {
                    $params['query'] = $searchComponent->queryConverter->toString($searchQuery);
                }
                ?>
                <li class="facet-value"><a href="<?= Url::current($params) ?>"><?= $value->getLabel() ?></a> (<?= $value->getResultsCount() ?>)</li>
            <?php endforeach ?>
            </ul>
            <?php endif ?>
        </li>
    <?php endforeach ?>
    </ul>
</nav>
<?php endif ?>