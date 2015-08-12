<?php

namespace im\search\components;

use im\eav\models\Attribute;
use im\search\models\EntityAttribute;
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
        $model = $this->getModel();
        /** @var \im\base\components\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        $entityType = $typesRegister->getEntityType($model);

        $attributes = $model->attributes();
        $labels = $model->attributeLabels();
        $searchableAttributes = [];
        $key = 0;
        foreach ($attributes as $attribute) {
            $searchableAttributes[$key] = new EntityAttribute([
                'entity_type' => $entityType,
                'name' => $attribute,
                'label' => isset($labels[$attribute]) ? $labels[$attribute] : $model->generateAttributeLabel($attribute)
            ]);
            $key++;
        }

        $eavAttributes = Attribute::findByEntityType($entityType);
        foreach ($eavAttributes as $attribute) {
            $searchableAttributes[$key] = new EntityAttribute([
                'entity_type' => $entityType,
                'attribute_id' => $attribute->id,
                'name' => $attribute->getName(),
                'label' => $attribute->getPresentation()
            ]);
            $key++;
        }

        return $searchableAttributes;
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