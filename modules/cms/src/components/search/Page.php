<?php

namespace im\cms\components\search;

use im\search\components\searchable\AttributeDescriptor;
use im\search\components\service\db\SearchableType;

/**
 * Class Page
 * @package im\cms\components\search
 */
class Page extends SearchableType
{
    /**
     * @inheritdoc
     */
    public function getFullTextSearchAttributes()
    {
        return [
            new AttributeDescriptor(['name' => 'title']),
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
