<?php

namespace im\search\components\service\db;

use im\eav\models\Attribute;
use im\eav\models\AttributeValue;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\searchable\SearchableInterface;
use ReflectionClass;
use Yii;
use yii\base\Model;
use yii\base\Object;
use yii\db\ActiveRecord;
use yii\elasticsearch\ActiveQuery;
use yii\helpers\Inflector;

/**
 * Searchable type for active record models.
 *
 * @package im\search\components\service\db
 */
class SearchableType extends Object implements SearchableInterface
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $searchServiceId;

    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var Model instance
     */
    protected $model;

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function getClass()
    {
        return $this->modelClass;
    }

    /**
     * @inheritdoc
     */
    public function getSearchService()
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');

        return $searchManager->getSearchService($this->searchServiceId);
    }

    /**
     * @inheritdoc
     */
    public function getSearchableAttributes($recursive = true)
    {
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
        $model = $this->getModel();
        if ($model instanceof AttributeValue) {
            return [];
        }
        $entityType = $typesRegister->getEntityType($model);
        $attributes = $model->attributes();
        $labels = $model->attributeLabels();
        $searchableAttributes = [];
        $key = 0;
        // Attributes
        foreach ($attributes as $attribute) {
            $searchableAttributes[$key] = new AttributeDescriptor([
                'entity_type' => $entityType,
                'name' => $attribute,
                'label' => isset($labels[$attribute]) ? $labels[$attribute] : $model->generateAttributeLabel($attribute)
            ]);
            $key++;
        }

        // EAV
        $eavAttributes = Attribute::findByEntityType($entityType);
        foreach ($eavAttributes as $attribute) {
            $searchableAttributes[$key] = new AttributeDescriptor([
                'entity_type' => $entityType,
                'name' => $attribute->getName() . '_attr',
                'label' => $attribute->getPresentation()
            ]);
            $key++;
        }

        // Relations
        $class = new \ReflectionClass($model);
        foreach ($class->getMethods() as $method) {
            if ($method->isPublic()) {
                $methodName = $method->getName();
                if (strpos($methodName, 'get') === 0) {
                    $math = false;
                    $name = substr($methodName, 3);
                    if (substr($name, -8) === 'Relation') {
                        $name = substr($name, 0, -8);
                        $math = true;
                    } elseif (preg_match('/@return.*ActiveQuery/i', $method->getDocComment())) {
                        $math = true;
                    }
                    if ($math && $name) {
                        /** @var ActiveQuery $relation */
                        $relation = $model->$methodName();
                        $modelClass = $relation->modelClass;
                        $searchableType = $searchManager->getSearchableTypeByClass($modelClass);
                        if (!$searchableType) {
                            $reflector = new ReflectionClass($modelClass);
                            /** @var SearchableInterface $searchableType */
                            $searchableType = Yii::createObject([
                                'class' => 'im\search\components\service\db\SearchableType',
                                'type' => Inflector::camel2id($reflector->getShortName(), '_'),
                                'modelClass' => $modelClass
                            ]);
                        }
                        $searchableAttributes[$key] = new AttributeDescriptor([
                            'entity_type' => $entityType,
                            'name' => Inflector::variablize(substr($methodName, 3)),
                            'label' => Inflector::titleize($name),
                            'value' => function ($model) use ($methodName) {
                                return $model->$methodName();
                            },
                            'type' => $searchableType->getType()
                        ]);
                        $key++;
                        if ($recursive) {
                            foreach ($searchableType->getSearchableAttributes(false) as $attribute) {
                                $attribute->label = Inflector::titleize($name) . ' >> ' . $attribute->label;
                                $attribute->name = Inflector::variablize(substr($methodName, 3)) . '.' . $attribute->name;
                                $searchableAttributes[$key] = $attribute;
                                $key++;
                            }
                        }
                    }
                }
            }
        }

        return $searchableAttributes;
    }

    /**
     * @return ActiveRecord
     */
    protected function getModel()
    {
        if (!$this->model) {
            $this->model = Yii::createObject($this->modelClass);
        }

        return $this->model;
    }

    protected function createAttribute()
    {

    }
}