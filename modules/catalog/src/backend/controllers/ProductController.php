<?php

namespace im\catalog\backend\controllers;

use im\base\controllers\CrudController;
use im\catalog\models\Product;
use im\catalog\models\ProductSearch;
use im\catalog\Module;
use Yii;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends CrudController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->successCreate))
            $this->successCreate = Module::t('products', 'Product has been successfully created.');
        if (!isset($this->errorCreate))
            $this->errorCreate = Module::t('products', 'Product has not been created. Please try again!');
        if (!isset($this->successUpdate))
            $this->successUpdate = Module::t('products', 'Product has been successfully saved.');
        if (!isset($this->successBatchUpdate))
            $this->successBatchUpdate = Module::t('products', '{count} products have been successfully saved.');
        if (!isset($this->errorUpdate))
            $this->errorUpdate = Module::t('products', 'Product has not been saved. Please try again!');
        if (!isset($this->successDelete))
            $this->successDelete = Module::t('products', 'Product has been successfully deleted.');
        if (!isset($this->successBatchDelete))
            $this->successBatchDelete = Module::t('products', 'Products have been successfully deleted.');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Product::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return ProductSearch::className();
    }

    /**
     * @inheritdoc
     */
    protected function createModel()
    {
        return Yii::createObject($this->getModelClass());
    }
}
