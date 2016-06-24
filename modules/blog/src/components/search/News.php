<?php

namespace im\blog\components\search;

use im\search\components\searchable\AttributeDescriptor;
use im\search\components\service\db\SearchableType;

/**
 * Class News
 * @package im\blog\components\search
 */
class News extends SearchableType
{
    /**
     * @inheritdoc
     */
    public function getFullTextSearchAttributes()
    {
        return [
            new AttributeDescriptor(['name' => 'title']),
            new AttributeDescriptor(['name' => 'announce']),
            new AttributeDescriptor(['name' => 'content'])
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
