<?php

namespace im\catalog\models\widgets;

use im\base\context\ModelContextInterface;
use im\catalog\models\ProductCategory;
use im\catalog\Module;
use im\cms\models\widgets\Widget;

/**
 * Product categories list widget.
 *
 * @package im\catalog\models\widgets
 */
class ProductCategoriesList extends Widget
{
    const TYPE = 'categories';

    /**
     * @var int
     */
    public $depth;

    /**
     * @inheritdoc
     */
    public function getCMSTitle()
    {
        return Module::t('module', 'Product categories list widget');
    }

    /**
     * @inheritdoc
     */
    public function getCMSDescription()
    {
        return Module::t('module', 'Widget for displaying product categories list');
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/catalog/backend/views/widgets/product-categories-widget/_form';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->context instanceof ModelContextInterface && ($model = $this->context->getModel()) && $model instanceof ProductCategory) {
            $root = $model;
        } else {
            $root = ProductCategory::find()->roots()->active()->one();
        }
        $items = ProductCategory::buildNodeTree($root, $root->children()->active()->all());

        return $this->render('product_categories_list', [
            'widget' => $this,
            'items' => $items
        ]);
    }
}