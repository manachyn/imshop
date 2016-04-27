<?php

namespace im\search\models;

use im\search\components\query\facet\TermsFacetInterface;
use im\search\components\SearchManager;
use Yii;

/**
 * Class SearchableTypesFacet
 * @package im\catalog\models
 */
class SearchableTypesFacetOld extends Facet implements TermsFacetInterface
{
    const TYPE = 'searchable_types_facet';

    /**
     * @var SearchableTypesFacetValue[]
     */
    protected $values = [];

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @inheritdoc
     */
    public function setValues($values)
    {
        foreach ($values as $value) {
            $value->setFacet($this);
        }
        $this->values = $values;
    }

    /**
     * @inheritdoc
     */
    public function isDisplayValuesWithoutResults()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getValueInstance(array $config)
    {
        return new SearchableTypesFacetValue($config);
    }

    /**
     * @inheritdoc
     */
    public function getValueInstances(array $configs)
    {
        /** @var SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
        $searchableTypes = $searchManager->getSearchableTypes();
        $values = [];
        foreach ($searchableTypes as $searchableType) {
            $values[] = new SearchableTypesFacetValue(['key' => $searchableType->getType()]);
        }

        return $values;
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/search/backend/views/facet-searchable-types/_form.php';
    }
}