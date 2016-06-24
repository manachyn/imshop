<?php

namespace im\search\models;

use im\base\behaviors\RelationsBehavior;
use im\search\components\query\facet\FacetInterface;

use im\search\components\query\SearchQueryInterface;
use im\search\components\searchable\AttributeDescriptor;
use im\search\Module;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Facet model class.
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property string $entity_type
 * @property string $attribute_name
 * @property string $index_name
 * @property string $type
 * @property string $operator
 * @property bool $multivalue
 *
 * @property FacetValue[] $valuesRelation
 */
class Facet extends ActiveRecord implements FacetInterface
{
    const TYPE_TERMS = 'terms_facet';
    const TYPE_RANGE = 'range_facet';
    const TYPE_INTERVAL = 'interval_facet';
    const TYPE_DEFAULT = self::TYPE_TERMS;

    const TYPE = self::TYPE_DEFAULT;

    const OPERATOR_OR = 'or';
    const OPERATOR_AND = 'and';
    const OPERATOR_DEFAULT = self::OPERATOR_OR;

    /**
     * @var SearchQueryInterface
     */
    private $_filter;

    /**
     * @var mixed
     */
    private $_context;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->type = static::TYPE;
        $this->multivalue = true;
    }

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        $row['type'] ?: self::TYPE_DEFAULT;

        return Yii::$app->get('searchManager')->getFacetInstance($row['type']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'relations' => [
                'class' => RelationsBehavior::className(),
                'settings' => [
                    'values' => ['deleteOnUnlink' => true]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%facets}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'index_name'/*, 'entity_type', 'attribute_name'*/, 'type'], 'required'],
            [['label'], 'string', 'max' => 255],
            [['name', 'index_name', 'entity_type', 'type'], 'string', 'max' => 100],
            [['operator'], 'string', 'max' => 3],
            [['multivalue'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('facet', 'ID'),
            'name' => Module::t('facet', 'Name'),
            'label' => Module::t('facet', 'Label'),
            'entity_type' => Module::t('facet', 'Entity type'),
            'attribute_name' => Module::t('facet', 'Attribute name'),
            'index_name' => Module::t('facet', 'Attribute name in index'),
            'type' => Module::t('facet', 'Type'),
            'operator' => Module::t('facet', 'Operator'),
            'multivalue' => Module::t('facet', 'Multivalue'),
            'from' => Module::t('facet', 'From'),
            'to' => Module::t('facet', 'To'),
            'interval' => Module::t('facet', 'Interval'),
            'values' => Module::t('facet', 'Values')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValuesRelation()
    {
        return $this->hasMany(FacetValue::className(), ['facet_id' => 'id'])->inverseOf('facetRelation');
    }

    /**
     * Returns array of available facet types
     * @return array
     */
    public static function getTypesList()
    {
        return ArrayHelper::map(Yii::$app->get('searchManager')->getFacetTypes(), 'type', 'name');
    }

    /**
     * Returns array of available facet value types
     * @return array
     */
    public static function getValueTypesList()
    {
        return ArrayHelper::map(Yii::$app->get('searchManager')->getFacetValueTypes(), 'type', 'name');
    }

    /**
     * Returns array of searchable attributes
     * @param string $entityType
     * @return array
     */
    public static function getSearchableAttributes($entityType)
    {
        if ($entityType) {
            /** @var \im\search\components\SearchManager $searchManager */
            $searchManager = Yii::$app->get('searchManager');
            return ArrayHelper::map($searchManager->getSearchableAttributes($entityType), function (AttributeDescriptor $attribute) {
                return $attribute->name;
            }, 'label');
        } else {
            return [];
        }
    }

    /**
     * Returns array of entity types
     * @return array
     */
    public static function getEntityTypesList()
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');

        return $searchManager->getSearchableTypeNames();
    }

    /**
     * Returns array of facet operators
     * @return array
     */
    public static function getOperatorsList()
    {
        return [
            self::OPERATOR_OR => Module::t('facet', 'OR'),
            self::OPERATOR_AND => Module::t('facet', 'AND')
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!$this->type) {
            $this->type = static::TYPE;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->label ?: Inflector::titleize($this->name);
    }

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
    public function getField()
    {
        return $this->index_name ?: $this->attribute_name;
    }

    /**
     * @inheritdoc
     */
    public function isMultivalue()
    {
        return (bool) $this->multivalue;
    }

    /**
     * @inheritdoc
     */
    public function getOperator()
    {
        return $this->operator ?: self::OPERATOR_DEFAULT;
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        if (!$this->isRelationPopulated('values')) {
            $values = $this->getValuesRelation()->orderBy('sort')->findFor('values', $this);
            $this->populateRelation('values', $values);
        }

        return $this->getRelatedRecords()['values'];
    }

    /**
     * @inheritdoc
     */
    public function setValues($values)
    {
        foreach ($values as $value) {
            $value->setFacet($this);
        }
        $this->populateRelation('values', $values);
    }

    /**
     * @inheritdoc
     */
    public function setFilter(SearchQueryInterface $filter)
    {
        $this->_filter = $filter;
    }

    /**
     * @inheritdoc
     */
    public function getFilter()
    {
        return $this->_filter;
    }

    /**
     * @inheritdoc
     */
    public function getValueInstance(array $config)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getValueInstances(array $configs)
    {
        $instances = [];
        foreach ($configs as $config) {
            $instances[] = $this->getValueInstance($config);
        }

        return $instances;
    }

    /**
     * @inheritdoc
     */
    public function setContext($context)
    {
        $this->_context = $context;
    }

    /**
     * @inheritdoc
     */
    public function getContext()
    {
        return $this->_context;
    }

    /**
     * @inheritdoc
     */
    public function isHasResults()
    {
        foreach ($this->getValues() as $value) {
            if ($value->getResultsCount()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function isDisplayValuesWithoutResults()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isDisplaySelectedValues()
    {
        return true;
    }

    /**
     * The name of the facet edit view
     * This should be in the format of 'path/to/view'.
     * @return string
     */
    public function getEditView()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        return parent::load($data, $formName) && $this->loadValues($data);
    }

    /**
     * Populates the facet values with the data array.
     *
     * @param array $data
     * @param string $formName
     * @return boolean
     */
    public function loadValues($data, $formName = null)
    {
        if ($formName === null) {
            $formName = 'FacetValues';
        }
        $values = $this->getValuesFromData($data, $formName);
        $loaded = [];
        $isLoaded = true;
        if (!empty($data[$formName])) {
            foreach ($data[$formName] as $key => $valueData) {
                $value = null;
                if (isset($values[$key])) {
                    $value = $values[$key];
                    if ($value->load($valueData, '')) {
                        $loaded[] = $value;
                    } else {
                        $isLoaded = false;
                    }
                }
            }
        }
        if (isset($data[$formName])) {
            $this->valuesRelation = $loaded ?: null;
        }

        return $isLoaded;
    }

    /**
     * Gets facet values from data array.
     *
     * @param array $data
     * @param string $formName
     * @return FacetValue[]
     */
    protected function getValuesFromData($data, $formName = null)
    {
        if ($formName === null) {
            $formName = 'FacetValues';
        }
        $values = [];
        if (!empty($data[$formName])) {
            $pks = [];
            $existingValues = [];
            foreach ($data[$formName] as $valueData) {
                if (!empty($valueData['id'])) {
                    $pks[] = $valueData['id'];
                }
            }
            if ($pks) {
                $existingValues = FacetValue::find()->where(['id' => $pks])->indexBy('id')->all();
            }
            /** @var \im\search\components\SearchManager $searchManager */
            $searchManager = Yii::$app->get('searchManager');
            foreach ($data[$formName] as $key => $valueData) {
                if (!empty($valueData['id']) && isset($existingValues[$valueData['id']])) {
                    $values[$key] = $existingValues[$valueData['id']];
                } else {
                    $values[$key] = isset($valueData['type']) ? $searchManager->getFacetValueInstance($valueData['type']) : new FacetValue();
                }
            }
        }

        return $values;
    }
}
