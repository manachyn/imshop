<?php

namespace im\catalog\models;

use im\search\components\query\facet\TreeFacetHelper;
use im\search\components\query\facet\TreeFacetInterface;
use im\search\models\Facet;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class CategoriesFacet extends Facet implements TreeFacetInterface
{
    const TYPE = 'categories_facet';

    /**
     * @var CategoriesFacetValue[]
     */
    protected $values = [];

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @inheritdoc
     */
    public function setValues($values)
    {
        foreach ($values as $value) {
            $value->setFacet($this);
        }
        $this->values = $values;
    }

    /**
     * @inheritdoc
     */
    public function getValueInstance(array $config)
    {
        if (!isset($config['class'])) {
            $config['class'] = $this->getValueClass();
        }
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->getModelClass();
        $instance = Yii::createObject($config);
        $attribute = $this->attribute_name ?: 'id';
        $category = $modelClass::findOne([$attribute => $instance->getKey()]);
        $instance->setEntity($category);

        return $instance;
    }

    /**
     * @inheritdoc
     */
    public function getValueInstances(array $configs)
    {
        /** @var CategoriesFacetValue[] $instances */
        $instances = [];
        foreach ($configs as $config) {
            if (!isset($config['class'])) {
                $config['class'] = $this->getValueClass();
            }
            $instances[] = Yii::createObject($config);
        }
        $attribute = $this->attribute_name ?: 'id';
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->getModelClass();
        $categories = $modelClass::find()
            ->where([$attribute => ArrayHelper::getColumn($instances, 'key')])->indexBy($attribute)->all();
        foreach ($instances as $key => $instance) {
            if (isset($categories[$instance->getKey()])) {
                $instance->setEntity($categories[$instance->getKey()]);
            }
        }

        return $instances;
    }

    /**
     * @inheritdoc
     */
    public function getValuesTree()
    {
        return TreeFacetHelper::buildValuesTree($this->getValues());
    }

    /**
     * Returns value class.
     *
     * @return string
     */
    protected function getValueClass()
    {
        return 'im\catalog\models\CategoriesFacetValue';
    }

    /**
     * Returns model class.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return 'im\catalog\models\Category';
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/catalog/backend/views/categories-facet/_form.php';
    }
}