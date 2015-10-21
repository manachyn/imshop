<?php

namespace im\catalog\models;

use im\search\components\query\facet\EntityFacetValueInterface;
use im\search\components\query\facet\FacetValueTrait;
use im\search\components\query\facet\TreeFacetValueInterface;
use yii\base\Object;

class CategoriesFacetValue extends Object implements EntityFacetValueInterface, TreeFacetValueInterface
{
    use FacetValueTrait;

    /**
     * @var Category
     */
    protected $_category;

    /**
     * @var CategoriesFacetValue[]
     */
    private $_children = [];

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->_category->name;
    }

    /**
     * @inheritdoc
     */
    public function getEntity()
    {
        return $this->_category;
    }

    /**
     * @inheritdoc
     */
    public function setEntity($entity)
    {
        $this->_category = $entity;
    }

    /**
     * @inheritdoc
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * @inheritdoc
     */
    public function setChildren($children)
    {
        $this->_children = $children;
    }
}