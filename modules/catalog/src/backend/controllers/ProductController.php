<?php

namespace im\catalog\backend\controllers;

use im\base\controllers\CrudController;
use im\catalog\components\ProductTypeInterface;
use im\catalog\models\Product;
use im\catalog\models\ProductSearch;
use im\catalog\models\ProductType;
use im\catalog\Module;
use im\eav\models\Attribute;
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

    public function actionAddAttributes()
    {
        if ((($attributes = Yii::$app->request->post('attributes')) || ($type = Yii::$app->request->post('type')))
            && ($formConfig = Yii::$app->request->post('form'))) {
            if ($attributes) {
                $attributes = Attribute::findAll(['id' => $attributes]);
            } elseif (isset($type)) {
                /** @var ProductTypeInterface $type */
                $type = ProductType::findOne($type);
                $attributes = $type->getEAttributes();
            }
            if ($attributes) {
                $values = [];
                foreach ($attributes as $attribute) {
                    $value = Product::getAttributeValueInstance();
                    $value->setEAttribute($attribute);
                    $values[$attribute->getName()] = $value;
                }
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_add_attributes', [
                        'attributes' => $values,
                        'formConfig' => $formConfig
                    ]);
                }
            }
        }
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
        return \Yii::createObject($this->getModelClass());
    }
}
