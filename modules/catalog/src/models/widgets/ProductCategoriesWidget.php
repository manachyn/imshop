<?php

namespace im\catalog\models\widgets;

use im\catalog\components\CategoryContextInterface;
use im\catalog\models\ProductCategory;
use im\catalog\Module;
use im\cms\models\widgets\Widget;
use im\tree\widgets\Tree;

class ProductCategoriesWidget extends Widget
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
        if (!$this->context instanceof CategoryContextInterface || !($root = $this->context->getCategory())) {
            /** @var ProductCategory $root */
            $root = ProductCategory::find()->roots()->active()->one();
        }
        $items = ProductCategory::buildNodeTree($root, $root->children()->active()->all());

        return Tree::widget([
            'items' => $items,
            'itemView' => '@im/catalog/views/product-category/_tree_item',
            'depth' => $this->depth
        ]);
    }
}