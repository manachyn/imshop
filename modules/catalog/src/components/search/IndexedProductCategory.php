<?php

namespace im\catalog\components\search;

use im\search\components\service\db\IndexedSearchableType;

/**
 * Class IndexedProductCategory
 * @package im\catalog\components\search
 */
class IndexedProductCategory extends IndexedSearchableType
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
}