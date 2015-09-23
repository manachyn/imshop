<?php

namespace im\search\components\searchable;

use im\eav\models\Attribute;
use im\search\components\index\provider\IndexProviderInterface;
use im\search\components\transformer\DocumentToObjectTransformerInterface;
use im\search\models\IndexAttribute;
use Yii;
use yii\base\Model;
use yii\base\Object;

class SearchableActiveRecord extends Object implements SearchableInterface
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var string|array|IndexProviderInterface
     */
    private $_indexProvider = 'im\search\components\index\provider\ActiveRecordIndexProvider';

    /**
     * @var string|array|DocumentToObjectTransformerInterface
     */
    private $_transformer = 'im\search\components\transformer\DocumentToActiveRecordTransformer';

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
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        $entityType = $typesRegister->getEntityType($model);

        $attributes = $model->attributes();
        $labels = $model->attributeLabels();
        $searchableAttributes = [];
        $key = 0;
        foreach ($attributes as $attribute) {
            $searchableAttributes[$key] = new AttributeDescriptor([
                'entity_type' => $entityType,
                'name' => $attribute,
                'label' => isset($labels[$attribute]) ? $labels[$attribute] : $model->generateAttributeLabel($attribute)
            ]);
            $key++;
        }

        $eavAttributes = Attribute::findByEntityType($entityType);
        foreach ($eavAttributes as $attribute) {
            $searchableAttributes[$key] = new AttributeDescriptor([
                'entity_type' => $entityType,
                'name' => 'eAttributes.' . $attribute->getName(),
                'label' => $attribute->getPresentation()
            ]);
            $key++;
        }

        return $searchableAttributes;
    }

    /**
     * @inheritdoc
     */
    public function getIndexableAttributes()
    {
        $model = $this->getModel();
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        $entityType = $typesRegister->getEntityType($model);
        $indexableAttributes = IndexAttribute::findByIndexType($entityType);
        $searchableAttributes = $this->getSearchableAttributes();
        $attributes = [];
        foreach ($indexableAttributes as $indexableAttribute) {
            foreach ($searchableAttributes as $searchableAttribute) {
                if ($indexableAttribute->index_type === $searchableAttribute->entity_type
                    && $indexableAttribute->name === $searchableAttribute->name) {
                    if ($indexableAttribute->type) {
                        $searchableAttribute->type = $indexableAttribute->type;
                    }
                    $attributes[] = $searchableAttribute;
                }
            }
        }

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function getIndexProvider()
    {
        if (!$this->_indexProvider instanceof IndexProviderInterface) {
            if (is_string($this->_indexProvider)) {
                $this->_indexProvider = ['class' => $this->_indexProvider];
            }
            $this->_indexProvider = Yii::createObject($this->_indexProvider);
            $this->_indexProvider->setModelClass($this->modelClass);
        }

        return $this->_indexProvider;
    }

    /**
     * @inheritdoc
     */
    public function getTransformer()
    {
        if (!$this->_transformer instanceof DocumentToObjectTransformerInterface) {
            if (is_string($this->_transformer)) {
                $this->_transformer = ['class' => $this->_transformer];
            }
            $this->_transformer = Yii::createObject($this->_transformer);
            $this->_transformer->setObjectClass($this->modelClass);
        }

        return $this->_transformer;
    }

    /**
     * @return Model
     */
    private function getModel()
    {
        if (!$this->_model) {
            $this->_model = Yii::createObject($this->modelClass);
        }

        return $this->_model;
    }


}