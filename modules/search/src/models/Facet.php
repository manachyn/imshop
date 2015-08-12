<?php

namespace im\search\models;

use im\search\backend\Module;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Facet model class.
 *
 * @property integer $id
 * @property string $name
 * @property string $entity_type
 * @property integer $attribute_id
 * @property string $attribute_name
 * @property string $type
 */
class Facet extends ActiveRecord
{
    const TYPE_TERMS = 'terms';
    const TYPE_RANGE = 'range';
    const TYPE_INTERVAL = 'interval';

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
            [['name', 'entity_type', 'type'], 'required'],
//            ['attribute_id', 'required', 'when' => function($model) {
//                return empty($model->attribute_name);
//            }],
//            ['attribute_name', 'required', 'when' => function($model) {
//                return empty($model->attribute_id);
//            }],
            [['name'], 'string', 'max' => 255],
            [['entity_type', 'type'], 'string', 'max' => 100],
            [['searchableAttribute'], 'safe']
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
            'entity_type' => Module::t('facet', 'Entity type'),
            'attribute_id' => Module::t('facet', 'Attribute ID'),
            'attribute_name' => Module::t('facet', 'Attribute name'),
            'searchableAttribute' => Module::t('facet', 'Attribute'),
        ];
    }

    /**
     * Returns array of available facet types
     * @return array
     */
    public static function getTypesList()
    {
        return [
            self::TYPE_TERMS => Module::t('facet', 'Terms'),
            self::TYPE_RANGE => Module::t('facet', 'Range'),
            self::TYPE_INTERVAL => Module::t('facet', 'Interval')
        ];
    }

    /**
     * Returns facet attribute id or name.
     *
     * @return int|string
     */
    public function getSearchableAttribute()
    {
        return $this->attribute_id ? $this->attribute_id : $this->attribute_name;
    }

    public function setSearchableAttribute($searchableAttribute)
    {
        if (is_numeric($searchableAttribute)) {
            $this->attribute_id = (int) $searchableAttribute;
            $this->attribute_name = '';
        } else {
            $this->attribute_name = $searchableAttribute;
            $this->attribute_id = null;
        }
    }

    /**
     * Returns array of searchable attributes
     * @param string $entityType
     * @return array
     */
    public static function getSearchableAttributes($entityType)
    {
        if ($entityType) {
            /** @var \im\search\components\Search $search */
            $search = Yii::$app->get('search');
            return ArrayHelper::map($search->getSearchableAttributes($entityType), function ($attribute) {
                return isset($attribute['id']) ? $attribute['id'] : $attribute['name'];
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
        /** @var \im\search\components\Search $search */
        $search = Yii::$app->get('search');

        return $search->getSearchableEntityTypes();
    }


}
