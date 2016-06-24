<?php

namespace im\search\widgets;

use im\cms\models\widgets\Widget;
use im\search\components\query\facet\FacetValueInterface;
use im\search\components\search\SearchResultContextInterface;
use im\search\Module;
use Yii;

/**
 * Facets widget class.
 *
 * @package im\search\widgets
 */
class FacetsWidget extends Widget
{
    const TYPE = 'facets';

    /**
     * @inheritdoc
     */
    public function getCMSTitle()
    {
        return Module::t('module', 'Facets widget');
    }

    /**
     * @inheritdoc
     */
    public function getCMSDescription()
    {
        return Module::t('module', 'Widget for displaying search facets');
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->context instanceof SearchResultContextInterface && ($result = $this->context->getResult())) {
            $searchQuery = $result->getQuery()->getSearchQuery();
            $selectedFacets = [];
            foreach ($result->getFacets() as $facet) {
                if ($facet->isDisplaySelectedValues() && ($values = $facet->getValues())) {
                    $selectedValues = array_filter($values, function (FacetValueInterface $value) use ($searchQuery) {
                        return $value->isSelected($searchQuery);
                    });
                    if ($selectedValues) {
                        $selectedFacet = clone $facet;
                        $selectedFacet->setValues($selectedValues);
                        $selectedFacets[] = $selectedFacet;
                    }
                }
            }
            return $this->render('facets/facets', [
                'facets' => $result->getFacets(),
                'selectedFacets' => $selectedFacets,
                'searchQuery' => $searchQuery
            ]);
        } else {
            return '';
        }
    }
}