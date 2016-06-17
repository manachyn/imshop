<?php

namespace im\catalog\components\search;

use im\catalog\models\Product as ProductModel;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\service\db\IndexedSearchableType;
use Yii;

/**
 * Class Product
 * @package im\catalog\components\search
 */
class Product extends IndexedSearchableType
{
    /**
     * @inheritdoc
     */
    public function getSearchableAttributes($recursive = true)
    {
        /** @var \im\catalog\models\Product $model */
        $model = $this->getModel();
        $entityType = 'product';

        $searchableAttributes = [];
        // Attributes
        $searchableAttributes = array_merge($searchableAttributes, $this->getSearchableModelAttributes($model));
        // EAV
        $searchableAttributes = array_merge($searchableAttributes, $this->getSearchableEAVAttributes($entityType));
        // Relations
        $searchableAttributes = array_merge($searchableAttributes, $this->getRelationAttributes($model->getCategoriesRelation(), 'categoriesRelation', 'categories'));
        $searchableAttributes[] = new AttributeDescriptor([
            'name' => 'all_categories',
            'label' => 'All categories (including  category parents)',
            'value' => function (ProductModel $model) {
                $allCategories = $categories = $model->getCategories();
                foreach ($categories as $category) {
                    $allCategories = array_merge($allCategories, $category->parents()->all());
                }
                $value = [];
                foreach ($allCategories as $category) {
                    $value[$category->id] = $category->id;
                }

                return array_values($value);
            }
        ]);
        $searchableAttributes = array_merge($searchableAttributes, $this->getRelationAttributes($model->getBrandRelation(), 'brandRelation', 'brand'));

        return $searchableAttributes;
    }

    /**
     * @inheritdoc
     */
    public function getTextFields()
    {
        return array_merge(parent::getTextFields(), ['title', 'description']);
    }
}
