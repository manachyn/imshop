<?php

namespace im\search\components;

use Yii;
use yii\base\Model;
use yii\base\Object;

class SearchProvider extends Object implements SearchProviderInterface
{
    /**
     * @var string model class
     */
    public $modelClass;

    /**
     * @var Model instance
     */
    private $_model;

    /**
     * @inheritdoc
     */
    public function getSearchableAttributes()
    {

    }

    /**
     * @inheritdoc
     */
    public function getIndexAttributes()
    {
        $model = $this->getModel();
        $attributes = $model->attributes();
        $labels = $model->attributeLabels();
        $indexAttributes = [];
        foreach ($attributes as $key => $attribute) {
            $indexAttributes[$key]['name'] = $attribute;
            $indexAttributes[$key]['label'] = isset($labels[$attribute]) ? $labels[$attribute] : $model->generateAttributeLabel($attribute);
        }

        return $indexAttributes;
    }

    /**
     * @return Model
     */
    protected function getModel()
    {
        if (!$this->_model) {
            $this->_model = Yii::createObject($this->modelClass);
        }

        return $this->_model;
    }


}