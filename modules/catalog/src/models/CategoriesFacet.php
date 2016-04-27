<?php

namespace im\catalog\models;

use Closure;
use creocoder\nestedsets\NestedSetsBehavior;
use im\search\components\query\facet\EntityFacetValueInterface;
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
     * @var array|Closure
     */
    protected $valueRouteParams;

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return $this->applyContext($this->values);
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
    public function isDisplayValuesWithoutResults()
    {
        return true;
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
        /** @var EntityFacetValueInterface $instance */
        $instance = Yii::createObject($config);
        $attribute = $this->attribute_name ?: 'id';
        if (!$instance->getEntity()) {
            $category = $modelClass::findOne([$attribute => $instance->getKey()]);
            $instance->setEntity($category);
        }

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
        $values = $this->getValues();
        if ($values) {
            /** @var Category[]|NestedSetsBehavior[] $categories */
            $categories = array_map(function (EntityFacetValueInterface $value) {
                return $value->getEntity();
            }, $values);
            /** @var \im\catalog\models\CategoryQuery $query */
            $query = null;
            /** @var Category|NestedSetsBehavior $root */
            $root = ($context = $this->getContext()) && $context instanceof Category ? $context : null;
            foreach ($categories as $category) {
                $parentsQuery = $category->parents();
                if ($root) {
                    $parentsQuery->andWhere(['and',
                        ['>', $category->leftAttribute, $root->getAttribute($root->leftAttribute)],
                        ['<', $category->rightAttribute, $root->getAttribute($root->rightAttribute)]
                    ]);
                }
                $query = $query ? $query->union($parentsQuery) : $parentsQuery;
            }
            $parents = $query->all();
            $parentsValues = [];
            $attribute = $this->attribute_name ?: 'id';
            foreach ($parents as $parent) {
                $parentsValues[] = $this->getValueInstance([
                    'entity' => $parent,
                    'key' => $parent->$attribute,
                    'facet' => $this
                ]);
            }
            $parentsValues = $this->applyContext($parentsValues);
            $values = array_merge($values, $parentsValues);
        }

        return $values ? TreeFacetHelper::buildValuesTree($values) : [];
    }

    /**
     * @param array|Closure $params
     */
    public function setValueRouteParams($params)
    {
        $this->valueRouteParams = $params;
    }

    /**
     * @return array|Closure
     */
    public function getValueRouteParams()
    {
        return $this->valueRouteParams;
    }

    /**
     * @param CategoriesFacetValue[] $values
     * @return CategoriesFacetValue[]
     */
    protected function applyContext($values)
    {
        //if (($context = $this->getContext()) && $context instanceof Category) {
            foreach ($values as $key => $value) {
//                if ($context->equals($value->getEntity())) {
//                    unset($values[$key]);
//                    continue;
//                }
                if ($valueRouteParams = $this->getValueRouteParams()) {
                    if ($valueRouteParams instanceof Closure) {
                        $valueRouteParams = call_user_func($valueRouteParams, $value);
                    }
                    $value->setRouteParams($valueRouteParams);
                }
            }
        //}

        return $values;
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