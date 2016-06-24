<?php

namespace im\catalog\components\search;

use im\search\components\searchable\AttributeDescriptor;
use im\search\components\service\db\SearchableType;

/**
 * Class ProductCategory
 * @package im\catalog\components\search
 */
class ProductCategory extends SearchableType
{
    /**
     * @inheritdoc
     */
    public function getSearchableAttributes($recursive = true)
    {
        $model = $this->getModel();
        $searchableAttributes = $this->getSearchableModelAttributes($model, ['tree', 'lft', 'rgt', 'depth', 'image_id', 'template_id']);

        return $searchableAttributes;
    }

    /**
     * @inheritdoc
     */
    public function getFullTextSearchAttributes()
    {
        return [
            new AttributeDescriptor(['name' => 'name']),
            new AttributeDescriptor(['name' => 'description']),
            new AttributeDescriptor(['name' => 'content'])
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSuggestionsAttributes()
    {
        return [
            new AttributeDescriptor(['name' => 'name'])
        ];
    }
}
