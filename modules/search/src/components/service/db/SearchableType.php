<?php

namespace im\search\components\service\db;

use im\eav\models\Attribute;
use im\eav\models\AttributeValue;
use im\search\components\index\IndexableInterface;
use im\search\components\query\parser\QueryParserContextInterface;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\searchable\AttributeDescriptorDependency;
use im\search\components\searchable\SearchableInterface;
use ReflectionClass;
use Yii;
use yii\base\Model;
use yii\base\Object;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

/**
 * Searchable type for active record models.
 *
 * @package im\search\components\service\db
 */
class SearchableType extends Object implements SearchableInterface, QueryParserContextInterface
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
     * @var string
     */
    public $searchResultsView;

    /**
     * @var bool
     */
    public $default = false;

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
    public function getSearchResultsView()
    {
        return $this->searchResultsView;
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

        $searchableAttributes = [];
        // Attributes
        $searchableAttributes = array_merge($searchableAttributes, $this->getSearchableModelAttributes($model));
        // EAV
        $searchableAttributes = array_merge($searchableAttributes, $this->getSearchableEAVAttributes($entityType));

        // Relations
        $class = new \ReflectionClass($model);
        foreach ($class->getMethods() as $method) {
            if ($method->isPublic()) {
                $methodName = $method->getName();
                if (strpos($methodName, 'get') === 0) {
                    $match = false;
                    $name = substr($methodName, 3);
                    if (substr($name, -8) === 'Relation') {
                        $name = substr($name, 0, -8);
                        $match = true;
                    } elseif (preg_match('/@return.*ActiveQuery/i', $method->getDocComment())) {
                        $match = true;
                    }
                    if ($match && $name) {
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
                        $searchableAttributes[] = new AttributeDescriptor([
                            'name' => Inflector::variablize(substr($methodName, 3)),
                            'label' => Inflector::titleize($name),
                            'value' => function ($model) use ($methodName) {
                                return $model->$methodName();
                            },
                            'type' => $searchableType->getType()
                        ]);
                        if ($recursive) {
                            foreach ($searchableType->getSearchableAttributes(false) as $attribute) {
                                $attribute->label = Inflector::titleize($name) . ' >> ' . $attribute->label;
                                $attribute->name = Inflector::variablize(substr($methodName, 3)) . '.' . $attribute->name;
                                $searchableAttributes[] = $attribute;
                            }
                        }
                    }
                }
            }
        }

        return $searchableAttributes;
    }

    /**
     * @inheritdoc
     */
    public function getFullTextSearchAttributes()
    {
        $fullTextSearchAttributes = [];
        if ($this instanceof IndexableInterface) {
            $fullTextSearchAttributes = array_filter($this->getIndexMapping(), function (AttributeDescriptor $attribute) {
                return !empty($attribute->params['fullTextSearch']);
            });
        }

        return $fullTextSearchAttributes;
    }

    /**
     * @inheritdoc
     */
    public function getTextFields()
    {
        return ['text'];
    }

    /**
     * @inheritdoc
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param ActiveRecord $model
     * @param array $except
     * @return AttributeDescriptor[]
     */
    protected function getSearchableModelAttributes($model, $except = [])
    {
        $searchableAttributes = [];
        $attributes = $model->attributes();
        $labels = $model->attributeLabels();
        foreach ($attributes as $attribute) {
            if (!in_array($attribute, $except)) {
                $searchableAttributes[] = new AttributeDescriptor([
                    'name' => $attribute,
                    'label' => isset($labels[$attribute]) ? $labels[$attribute] : $model->generateAttributeLabel($attribute)
                ]);
            }
        }

        return $searchableAttributes;
    }

    /**
     * @param string $entityType
     * @return AttributeDescriptor[]
     */
    protected function getSearchableEAVAttributes($entityType)
    {
        $searchableAttributes = [];
        $eavAttributes = Attribute::findByEntityType($entityType);
        foreach ($eavAttributes as $attribute) {
            $searchableAttributes[] = new AttributeDescriptor([
                'name' => $attribute->getName() . '_attr',
                'label' => $attribute->getPresentation()
            ]);
        }

        return $searchableAttributes;
    }

    /**
     * @param ActiveQuery $relation
     * @param string $name
     * @param string $label
     * @param bool $recursive
     * @return AttributeDescriptor[]
     */
    protected function getRelationAttributes(ActiveQuery $relation, $name, $label = '', $recursive = true)
    {
        $searchableAttributes = [];
        $label = $label ?: $name;
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
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
        $searchableAttributes[] = new AttributeDescriptor([
            'name' => Inflector::variablize($name),
            'label' => Inflector::titleize($label),
            'value' => function ($model) use ($relation) {
                return $relation;
            },
            'type' => $searchableType->getType(),
            'dependency' => new AttributeDescriptorDependency(['class' => $modelClass])
        ]);
        if ($recursive) {
            foreach ($searchableType->getSearchableAttributes(false) as $attribute) {
                $attribute->dependency = new AttributeDescriptorDependency([
                    'class' => $modelClass,
                    'attribute' => $attribute->value instanceof \Closure ? null : $attribute->name
                ]);
                $attribute->label = Inflector::titleize($name) . ' >> ' . $attribute->label;
                $attribute->name = Inflector::variablize($label) . '.' . $attribute->name;
                $searchableAttributes[] = $attribute;
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
}