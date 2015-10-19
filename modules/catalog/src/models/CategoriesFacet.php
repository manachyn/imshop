<?php

namespace im\catalog\models;

use im\search\components\query\facet\EntityFacetValueInterface;
use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\facet\TermsFacetInterface;
use im\search\models\Facet;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class CategoriesFacet extends Facet implements TermsFacetInterface
{
    const TYPE = 'categories_facet';

    /**
     * @var FacetValueInterface
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
        parent::setValues($values);
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
        /** @var EntityFacetValueInterface[] $instances */
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
}