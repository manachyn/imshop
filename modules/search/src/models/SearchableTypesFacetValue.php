<?php

namespace im\search\models;

use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\facet\FacetValueTrait;
use Yii;
use yii\base\Object;

/**
 * Class SearchableTypesFacetValue
 * @package im\search\models
 */
class SearchableTypesFacetValue extends Object implements FacetValueInterface
{
    use FacetValueTrait;

    /**
     * @inheritdoc
     */
    public function getRouteParams()
    {
        return ['type' => $this->getKey()];
    }
}