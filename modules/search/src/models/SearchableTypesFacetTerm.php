<?php

namespace im\search\models;
use im\search\components\searchable\SearchableInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Class SearchableTypesFacetTerm
 * @package im\search\models
 */
class SearchableTypesFacetTerm extends FacetTerm
{
    const TYPE = 'searchable_types_facet_term';

    /**
     * @inheritdoc
     */
    public function getRouteParams()
    {
        return ['type' => $this->getKey() == Yii::$app->get('searchManager')->getDefaultSearchableType()->getType() ? null : $this->getKey()];
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/search/backend/views/searchable-types-facet-term/_form';
    }

    /**
     * Returns array of available searchable types.
     *
     * @return array
     */
    public static function getSearchableTypesList()
    {
        return ArrayHelper::map(Yii::$app->get('searchManager')->getSearchableTypes(), 'type', function (SearchableInterface $searchableType) {
            return Inflector::titleize($searchableType->getType());
        });
    }
}