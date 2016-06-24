<?php

namespace im\catalog\components\search;

use im\search\components\searchable\AttributeDescriptor;
use im\search\components\service\db\SearchableType;

/**
 * Class Product
 * @package im\catalog\components\search
 */
class Product extends SearchableType
{
    use SearchableProductTrait;

    /**
     * @inheritdoc
     */
    public function getFullTextSearchAttributes()
    {
        return [
            new AttributeDescriptor(['name' => 'title']),
            new AttributeDescriptor(['name' => 'short_description']),
            new AttributeDescriptor(['name' => 'description'])
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSuggestionsAttributes()
    {
        return [
            new AttributeDescriptor(['name' => 'title'])
        ];
    }
}
