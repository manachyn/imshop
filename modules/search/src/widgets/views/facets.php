<?php

/* @var $this yii\web\View */
/* @var $facets im\search\components\query\FacetInterface[] */
?>

<?php foreach ($facets as $facet) : ?>
    <?= $facet->getLabel() ?>
    <ul>
    <?php foreach ($facet->getValues() as $value) : ?>
        <li><?= $value->getLabel() ?> (<?= $value->getResultsCount() ?>)</li>
    <?php endforeach ?>
    </ul>
<?php endforeach ?>