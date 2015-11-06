<?php

namespace im\search\models;

use im\search\components\query\facet\RangeFacetInterface;

/**
 * Range facet model class.
 */
class RangeFacet extends Facet implements RangeFacetInterface
{
    const TYPE = self::TYPE_RANGE;

    /**
     * @inheritdoc
     */
    public function getValueInstance(array $config)
    {
        return new FacetRange($config);
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '_form';
    }
}
