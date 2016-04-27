<?php

namespace im\catalog\backend\controllers;

use im\catalog\models\ProductCategory;
use im\catalog\models\ProductCategorySearch;

/**
 * ProductCategoryController implements the CRUD actions for ProductCategory model.
 */
class ProductCategoryController extends CategoryController
{
    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return ProductCategory::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return ProductCategorySearch::className();
    }
}
