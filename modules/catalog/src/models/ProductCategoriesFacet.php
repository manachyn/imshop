<?php

namespace im\catalog\models;

class ProductCategoriesFacet extends CategoriesFacet
{
    const TYPE = 'product_categories_facet';

    /**
     * @inheritdoc
     */
    protected function getValueClass()
    {
        return 'im\catalog\models\ProductCategoriesFacetValue';
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return 'im\catalog\models\ProductCategory';
    }
}