<?php

namespace im\catalog\components\search;

use im\search\components\service\db\IndexedSearchableType;
use Yii;

class ProductCategory extends IndexedSearchableType
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