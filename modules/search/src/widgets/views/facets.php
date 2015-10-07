<?php

/* @var $this yii\web\View */
/* @var $facets im\search\components\query\facet\FacetInterface[] */
?>

<?php if ($facets) : ?>
<nav>
    <ul class="facets">
    <?php foreach ($facets as $facet) : ?>
        <?php if ($values = $facet->getValues()) : ?>
        <li class="facet">
            <span class="facet-label"><?= $facet->getLabel() ?></span>
            <ul class="facet-values">
            <?php foreach ($values as $value) : ?>
                <li class="facet-value"><?= $value->getLabel() ?> (<?= $value->getResultsCount() ?>)</li>
            <?php endforeach ?>
            </ul>
            <?php endif ?>
        </li>
    <?php endforeach ?>
    </ul>
</nav>
<?php endif ?>