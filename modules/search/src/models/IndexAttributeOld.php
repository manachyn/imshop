<?php

namespace im\search\models;

use im\search\backend\Module;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Index attribute model class.
 *
 * @property integer $id
 * @property string $entity_type
 * @property integer $attribute_id
 * @property string $attribute_name
 * @property string $type
 *
 */
class IndexAttributeOld extends ActiveRecord
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $name;

    /**
     * @var boolean
     */
    public $indexable = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%index_attributes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_type'], 'required'],
            ['attribute_id', 'required', 'when' => function($model) {
                return empty($model->attribute_name);
            }],
            ['attribute_name', 'required', 'when' => function($model) {
                return empty($model->attribute_id);
            }],
            [['entity_type', 'attribute_name', 'type'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('indexAttribute', 'ID'),
            'name' => Module::t('indexAttribute', 'Name'),
            'type' => Module::t('indexAttribute', 'Type'),
            'entity_type' => Module::t('indexAttribute', 'Entity Type'),
            'label' => Module::t('indexAttribute', 'Label'),
            'indexable' => Module::t('indexAttribute', 'Indexable')
        ];
    }

    /**
     * Returns attributes by entity type.
     *
     * @param string $entityType
     * @param string|array $orderBy
     * @return static[]
     */
    public static function findByEntityType($entityType = null, $orderBy = null)
    {
        $query = static::find();
        if ($entityType) {
            $query->where(['entity_type' => $entityType]);
        }
        if ($orderBy) {
            $query->orderBy($orderBy);
        }

        return $query->all();
    }
}
